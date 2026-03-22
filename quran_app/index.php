<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quran Player</title>
    <style>
        body{font-family: Arial, sans-serif;background:#f7f7f7;color:#111}
        .container{max-width:1100px;margin:18px auto;padding:18px;background:#fff;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.06)}
        header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:12px;flex-wrap:wrap}
        select,button{padding:8px 10px;font-size:14px}
        #verses{margin-top:14px;border-top:1px solid #eee;padding-top:12px}
        .aya{padding:8px;border-radius:6px;margin-bottom:6px;background:#fafafa;transition:background .15s;cursor:pointer}
        .aya.playing{background:#fff7e6}
        .aya .arabic{font-size:24px;direction:rtl;text-align:right}
        .aya .meta{font-size:12px;color:#666;margin-top:6px}
        .controls{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
        .highlight-char{color:#e53e3e}
        .status{font-size:13px;color:#444;margin-left:8px}
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Quran Player</h1>
        <div class="controls">
            <label for="surah">Surah</label>
            <select id="surah"></select>

            <label for="reciter">Reciter folder</label>
            <select id="reciter" style="width:220px;padding:6px">
                <option value="afasy">afasy</option>
                <option value="alhabib">alhabib</option>
                <option value="custom">custom</option>
            </select>

            <button id="prevBtn">Prev</button>
            <button id="playToggle">Play</button>
            <button id="nextBtn">Next</button>
            <button id="playAll">Play All</button>
            <label>Speed</label>
            <select id="speed"><option>1</option><option>1.25</option><option>1.5</option><option>2</option></select>

            <div class="status" id="status">Ready</div>
        </div>
    </header>

    <div id="verses"></div>
</div>

<script>
const apiBase = 'api.php';
let verses = [];
let audio = new Audio();
let currentIndex = -1;
let playAllMode = false;
let isPlaying = false;


function pad3(n){
    const s = String(n || '');
    return s.padStart(3, '0');
}


async function fetchChapters(){
    try {
        const res = await fetch(`${apiBase}?endpoint=chapters`);
        const j = await res.json();
        const sel = document.getElementById('surah');
        sel.innerHTML = '';
        if (j.chapters){
            j.chapters.forEach(ch => {
                const opt = document.createElement('option');
                opt.value = ch.id;
                opt.textContent = ch.translated_name.name + ' — ' + ch.name_simple + ' ('+ch.id+')';
                sel.appendChild(opt);
            });
        }
    } catch (e) {
        console.error('Failed to load chapters', e);
        document.getElementById('status').textContent = 'Failed to load chapters';
    }
}


async function fetchVerses(chapter){
    document.getElementById('verses').innerHTML = 'Loading...';
    try {
        const res = await fetch(`${apiBase}?endpoint=verses&chapter=${chapter}`);
        const j = await res.json();
        verses = j.verses || j.data || [];
        renderVerses();
        document.getElementById('status').textContent = `Loaded ${verses.length} verses`;
    } catch (e) {
        console.error('Failed to load verses', e);
        document.getElementById('verses').innerHTML = 'Failed to load verses';
        document.getElementById('status').textContent = 'Error loading verses';
    }
}


function renderVerses(){
    const container = document.getElementById('verses'); container.innerHTML = '';
    verses.forEach((v,i)=>{
        const div = document.createElement('div'); div.className='aya'; div.id='aya-'+i;
        const arab = document.createElement('div'); arab.className='arabic';
        arab.innerHTML = wrapChars(v.text_uthmani || v.text || '');
        const meta = document.createElement('div'); meta.className='meta'; meta.textContent = `Aya ${v.verse_number || v.verse || (i+1)}`;
        div.appendChild(arab); div.appendChild(meta);
        container.appendChild(div);
        div.addEventListener('click', ()=>playIndex(i));
    });
}


function resolveAudioUrl(v){
    if (!v) return null;
    const chap = v.chapter_number || v.chapter || v.chapter_id || v.chapterId || '';
    const aya  = v.verse_number || v.verse || v.verse_id || v.verseNumber || '';
    if (!chap || !aya) return null;
    const rec = document.getElementById('reciter').value || 'afasy';
    const sss = pad3(Number(chap));
    const aaa = pad3(Number(aya));
    return `audio/${rec}/${sss}${aaa}.mp3`;
}

function wrapChars(text){
    return Array.from(text).map(c=>`<span class="char">${c}</span>`).join('');
}

function highlightAya(index){
    document.querySelectorAll('.aya').forEach(el=>el.classList.remove('playing'));
    if (index<0) return;
    const el = document.getElementById('aya-'+index);
    if (!el) return;
    el.classList.add('playing');
}

function clearCharHighlights(index){
    const el = document.getElementById('aya-'+index);
    if (!el) return;
    el.querySelectorAll('.char').forEach(s=>s.style.color='');
}

function updateCharHighlights(index){
    const el = document.getElementById('aya-'+index);
    if (!el || !audio.duration || audio.duration === 0) return;
    const chars = el.querySelectorAll('.char');
    if (chars.length === 0) return;
    const ratio = Math.min(1, Math.max(0, audio.currentTime / audio.duration));
    const to = Math.floor(ratio * chars.length);
    chars.forEach((ch, idx)=> ch.style.color = idx < to ? '#e53e3e' : '');
}

function playIndex(i){
    if (i<0 || i>=verses.length) return;
    currentIndex = i;
    const v = verses[i];
    const audioUrl = resolveAudioUrl(v);
    if (!audioUrl){
        console.warn('Audio not available for this verse', v);
        document.getElementById('status').textContent = 'Audio not available for this verse';
        return;
    }

    audio.src = audioUrl;
    audio.preload = 'auto';
    audio.playbackRate = parseFloat(document.getElementById('speed').value || '1');

    audio.onloadedmetadata = ()=> {
        clearCharHighlights(i);
        highlightAya(i);
        updateCharHighlights(i);
    };

    audio.ontimeupdate = ()=> {
        updateCharHighlights(i);
    };

    audio.onended = ()=> {
        clearCharHighlights(i);
        highlightAya(-1);
        isPlaying = false;
        document.getElementById('playToggle').textContent = 'Play';
        if (playAllMode){
            setTimeout(()=> playIndex(i+1), 150);
        } else {
            document.getElementById('status').textContent = `Finished Aya ${v.verse_number || v.verse || (i+1)}`;
        }
    };

    audio.onerror = (e) => {
        console.error('Audio error for', audioUrl, e);
        document.getElementById('status').textContent = `Failed to play ${audioUrl}`;
        if (playAllMode) {
            setTimeout(()=> playIndex(i+1), 200);
        }
    };

    audio.play().then(()=> {
        isPlaying = true;
        document.getElementById('playToggle').textContent = 'Pause';
        document.getElementById('status').textContent = `Playing Aya ${v.verse_number || v.verse || (i+1)}`;
        const next = i+1;
        if (next < verses.length){
            const nextUrl = resolveAudioUrl(verses[next]);
            if (nextUrl){
                const p = new Audio(); p.src = nextUrl; p.preload = 'auto';
            }
        }
    }).catch(err=>{
        console.error('Playback failed:', err);
        document.getElementById('status').textContent = 'Playback failed: ' + (err && err.message ? err.message : err);
    });
}

function playAll(){
    if (verses.length === 0) return;
    playAllMode = true;
    const start = currentIndex >= 0 ? currentIndex : 0;
    playIndex(start);
}

function togglePlayPause(){
    if (!audio.src){
        const start = currentIndex >= 0 ? currentIndex : 0;
        playIndex(start);
        return;
    }
    if (isPlaying){
        audio.pause();
        isPlaying = false;
        document.getElementById('playToggle').textContent = 'Play';
        document.getElementById('status').textContent = 'Paused';
    } else {
        audio.play().then(()=> {
            isPlaying = true;
            document.getElementById('playToggle').textContent = 'Pause';
            document.getElementById('status').textContent = 'Playing';
        }).catch(err=>{
            console.error('Resume failed', err);
            document.getElementById('status').textContent = 'Resume failed';
        });
    }
}

function playNext(){
    const next = (currentIndex < 0) ? 0 : currentIndex + 1;
    if (next < verses.length) {
        playAllMode = false;
        playIndex(next);
    }
}
function playPrev(){
    const prev = (currentIndex <= 0) ? 0 : currentIndex - 1;
    playAllMode = false;
    playIndex(prev);
}

document.getElementById('surah').addEventListener('change', (e)=>fetchVerses(e.target.value));
document.getElementById('playAll').addEventListener('click', ()=>playAll());
document.getElementById('playToggle').addEventListener('click', ()=>togglePlayPause());
document.getElementById('nextBtn').addEventListener('click', ()=>playNext());
document.getElementById('prevBtn').addEventListener('click', ()=>playPrev());
document.getElementById('speed').addEventListener('change', ()=>{ audio.playbackRate = parseFloat(document.getElementById('speed').value); });
document.getElementById('reciter').addEventListener('change', ()=>{ const surah = document.getElementById('surah').value; if (surah) fetchVerses(surah); });

fetchChapters().then(()=>{
    const sel = document.getElementById('surah');
    if (sel.options.length > 0){
        sel.selectedIndex = 0;
        fetchVerses(sel.value);
    } else {
        document.getElementById('status').textContent = 'No chapters found';
    }
});
</script>
</body>
</html>