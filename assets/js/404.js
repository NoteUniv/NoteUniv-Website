let context;

window.onload = function () {
    const WIDTH = window.innerWidth;
    const HEIGHT = window.innerHeight;

    let audio = document.getElementById("audio");
    audio.src = "assets/music/EROSYA_ANTHEM.mp3";
    audio.load();
    audio.volume = 0.05;
    context = new AudioContext();
    let src = context.createMediaElementSource(audio);
    let analyser = context.createAnalyser();
    let canvas = document.getElementById("canvas");
    canvas.width = WIDTH;
    canvas.height = HEIGHT;
    let ctx = canvas.getContext("2d");
    src.connect(analyser);
    analyser.connect(context.destination);
    if (WIDTH < 600) {
        analyser.fftSize = 128;
    } else {
        analyser.fftSize = 512;
    }
    let bufferLength = analyser.frequencyBinCount;
    let dataArray = new Uint8Array(bufferLength);
    let barWidth = (WIDTH / bufferLength) * 2.5;
    let barHeight, x;

    function renderFrame() {
        requestAnimationFrame(renderFrame);
        x = 0;
        analyser.getByteFrequencyData(dataArray);
        ctx.fillStyle = "white";
        ctx.fillRect(0, 0, WIDTH, HEIGHT);
        for (let i = 0; i < bufferLength; i++) {
            barHeight = dataArray[i];
            let r = (barHeight / 3) + (25 * (i / bufferLength));
            let g = 50 * (i / bufferLength);
            let b = 33;
            ctx.fillStyle = "rgb(" + r + "," + g + "," + b + ")";
            ctx.fillRect(x, HEIGHT - barHeight, barWidth, barHeight);
            x += barWidth + 1;
        }
    }
    try {
        context.resume();
        audio.play();
    } catch (error) {
        return;
    }
    renderFrame();
};

document.addEventListener("keydown", function (e) {
    if (e.code === "Space") {
        e.preventDefault();
        let audio = document.getElementById("audio");
        if (!audio.paused) {
            audio.pause();
        } else {
            audio.play();
        }
    }
}, false);

document.addEventListener("click", function () {
    context.resume();
    audio.play();
}, false);