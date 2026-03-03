<?php
// Quran App - Single file front-end served by XAMPP
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
        header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
        select,button{padding:8px 10px;font-size:14px}
        #verses{margin-top:14px;border-top:1px solid #eee;padding-top:12px}
        .aya{padding:8px;border-radius:6px;margin-bottom:6px;background:#fafafa;transition:background .15s}
        .aya.playing{background:#fff7e6}
        .aya .arabic{font-size:24px;direction:rtl;text-align:right}
        .aya .meta{font-size:12px;color:#666;margin-top:6px}
        .controls{display:flex;gap:8px;align-items:center}
        .highlight-char{color:#e53e3e}
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Quran Player</h1>
        <div class="controls">
            <label for="surah">Surah</label>
            <select id="surah"></select>
                <!-- choose a folder name corresponding to your local MP3 files -->
                <label for="reciter">Reciter folder</label>
                <select id="reciter" style="width:220px;padding:6px">
                    <option value="afasy">afasy</option>
                    <option value="alhabib">alhabib</option>
                    <option value="custom">custom</option>
                </select>
            <button id="playAll">Play All</button>
            <button id="pauseBtn">Pause</button>
            <label>Speed</label>
            <select id="speed"><option>1</option><option>1.25</option><option>1.5</option><option>2</option></select>
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
// note: audio files must be placed under quran_app/audio/{folder}/{surah}_{aya}.mp3
// example folder names: afasy, alhabib, custom
// you can edit the select options above to match your folders



async function fetchChapters(){
    const res = await fetch(`${apiBase}?endpoint=chapters`);
    const j = await res.json();
    const sel = document.getElementById('surah');
    sel.innerHTML = '';
    if (j.chapters){
        j.chapters.forEach(ch => {
            const opt = document.createElement('option'); opt.value = ch.id; opt.textContent = ch.translated_name.name + ' — ' + ch.name_simple + ' ('+ch.id+')';
            sel.appendChild(opt);
        });
    }
}



async function fetchVerses(chapter){
    document.getElementById('verses').innerHTML = 'Loading...';
    const res = await fetch(`${apiBase}?endpoint=verses&chapter=${chapter}`);
    const j = await res.json();
    verses = j.verses || j.data || [];
    renderVerses();
}

function renderVerses(){
    const container = document.getElementById('verses'); container.innerHTML = '';
    verses.forEach((v,i)=>{
        const div = document.createElement('div'); div.className='aya'; div.id='aya-'+i;
        const arab = document.createElement('div'); arab.className='arabic'; arab.innerHTML = wrapChars(v.text_uthmani || v.text);
        const meta = document.createElement('div'); meta.className='meta'; meta.textContent = `Aya ${v.verse_number}`;
        div.appendChild(arab); div.appendChild(meta);
        container.appendChild(div);
        div.addEventListener('click', ()=>playIndex(i));
    });
}

function resolveAudioUrl(v){
    if (!v) return null;
    const chap = v.chapter_number || v.chapter || '';
    const aya  = v.verse_number || v.verse_number || '';
    const rec = document.getElementById('reciter').value || 'afasy';
    return `audio/${rec}/${chap}_${aya}.mp3`;
}

function wrapChars(text){
    // split into characters and wrap in spans for highlighting
    return text.split('').map(c=>`<span class="char">${c}</span>`).join('');
}

function highlightAya(index){
    // clear previous
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

function progressiveHighlight(index){
    // use audio currentTime to highlight proportionally
    clearCharHighlights(index);
    const el = document.getElementById('aya-'+index);
    if (!el) return;
    const chars = el.querySelectorAll('.char');
    if (chars.length===0) return;
    // highlight will be updated in audio.ontimeupdate
}

function playIndex(i){
    if (i<0 || i>=verses.length) return;
    currentIndex = i;
    const v = verses[i];
    // pick audio URL if available
    const audioUrl = resolveAudioUrl(v);
    if (!audioUrl){ alert('Audio not available for this verse'); return; }
    audio.src = audioUrl;
    audio.preload = 'auto';
    audio.playbackRate = parseFloat(document.getElementById('speed').value || '1');
    // safer play with promise handling
    audio.play().then(()=>{ playing=true; }).catch(err=>{
        console.error('Playback failed:', err); alert('Playback failed: ' + (err && err.message ? err.message : err));
    });
    playAllMode=false;
    highlightAya(i);
    progressiveHighlight(i);
    // update highlights based on currentTime
    audio.ontimeupdate = ()=>{
        const el = document.getElementById('aya-'+i);
        if (!el || !audio.duration || audio.duration===0) return;
        const chars = el.querySelectorAll('.char');
        const ratio = Math.min(1, Math.max(0, audio.currentTime / audio.duration));
        const to = Math.floor(ratio * chars.length);
        chars.forEach((ch, idx)=> ch.style.color = idx < to ? '#e53e3e' : '');
    };
    audio.onended = ()=>{
        clearCharHighlights(i);
        if (playAllMode) playIndex(i+1);
    };
    // preload next
    const next = i+1;
    if (next < verses.length && verses[next].audio && verses[next].audio.url){
        const p = new Audio(); p.src = verses[next].audio.url; p.preload = 'auto';
    }
}

function playAll(){
    if (verses.length===0) return;
    playAllMode = true;
    playIndex(0);
}

function pauseAudio(){
    audio.pause(); playing=false;
}

// UI wiring
document.getElementById('surah').addEventListener('change', (e)=>fetchVerses(e.target.value));
document.getElementById('playAll').addEventListener('click', ()=>playAll());
document.getElementById('pauseBtn').addEventListener('click', ()=>pauseAudio());
document.getElementById('speed').addEventListener('change', ()=>{ audio.playbackRate = parseFloat(document.getElementById('speed').value); });

// initial load: chapters only
fetchChapters().then(()=>{
    const sel = document.getElementById('surah'); sel.selectedIndex=0; fetchVerses(sel.value);
});

// when reciter folder changed, reload verses to update audio path
document.getElementById('reciter').addEventListener('change', ()=>{
    const surah = document.getElementById('surah').value;
    if (surah) fetchVerses(surah);
});
</script>
</body>
</html>