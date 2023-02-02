function getId(id){return document.getElementById(id)}
function setSun(time, sunrise, sunset) {
    const background = getId("sunWeather")
    if (time < sunrise || time > sunset) background.classList.add("sunWeatherDark")
}
function create(tag = "div") {return document.createElement(tag)}

function getHour(time) {
    return new Date(time).getHours()
}
function setProgress(time) {
    const hour = getHour(time)
    const progress = getId("progressWeather")
    const parent = create()
    parent.classList.add("wrapProgressBar")
    for(let i = 0; i < 13; i++) {
        const wrap = create()
        wrap.classList.add("wrapBar")
        const child = create()
        child.classList.add("progressBar")
        if (i === hour) {
            child.classList.add("now")
        }
        wrap.append(child)
        if (i % 2 === 0) {
            const time = create()
            time.innerHTML = i
            wrap.append(time)
        }
        parent.append(wrap)
    }
    progress.append(parent)
}

function setDangerLevels(speed) {
    const kmh = speed * 3.6
    const levels = [{v:2,c:"66FFFF",d:"Calm"},{v:5,c:"00FFFF",d:"Light air"},{v:11,c:"00FF99",d:"Light breeze"},{v:19,c:"00FF99",d:"Gentle breeze"},
        {v:29,c:"66FF66",d:"Moderate breeze"},{v:39,c:"99FF33",d:"Fresh breeze"},{v:50,c:"CCFF33",d:"Strong breeze"},{v:61,c:"FFFF00",d:"Moderate gale"},{v:74,c:"FFC000",d:"Fresh gale"},
        {v:87,c:"FF9900",d:"Strong gale"},{v:101,c:"FF6600",d:"Whole gale"},{v:116,c:"FF3300",d:"Violent storm"},{v:117,c:"FF0000",d:"Hurricane"}]
    let level = 0
    for (let i = levels.length-1; i > 0; i--) {
        if (kmh >= levels[i].v) {
            level = i
            break
        }
    }
    const dangerLevels = getId("dangerLevels")
    for (let i = 0; i < 12; i++) {
        const child = create()
        child.classList.add("dangerLevel")
        child.style.backgroundColor = "#" + levels[i].c
        child.title = levels[i].d
        if (i <= level) child.classList.add("active")
        dangerLevels.append(child)
    }
}
function setArrow(rotation) {
    const arrow = getId("arrow")
    arrow.style.setProperty("--arrowRotation", rotation + "deg")
}