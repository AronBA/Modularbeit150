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
    const date = new Date();
    let hour = getHour(date)
    if (hour === 0) hour = 12
    const progress = getId("progressWeather")
    const parent = create()
    parent.classList.add("wrapProgressBar")
    for(let i = 1; i < 13; i++) {
        const wrap = create()
        wrap.classList.add("wrapBar")
        const child = create()
        child.classList.add("progressBar")
        if (i === hour || i === hour-12) child.classList.add("now")
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
    const levels = [
        {val:2,c:"66FFFF",d:"Calm"},{val:5,c:"00FFFF",d:"Light air"},{val:11,c:"00FF99",d:"Light breeze"},
        {val:19,c:"00FF99",d:"Gentle breeze"},{val:29,c:"66FF66",d:"Moderate breeze"},{val:39,c:"99FF33",d:"Fresh breeze"},
        {val:50,c:"CCFF33",d:"Strong breeze"},{val:61,c:"FFFF00",d:"Moderate gale"},{val:74,c:"FFC000",d:"Fresh gale"},
        {val:87,c:"FF9900",d:"Strong gale"},{val:101,c:"FF6600",d:"Whole gale"},{val:116,c:"FF3300",d:"Violent storm"},
        {val:9999,c:"FF0000",d:"Hurricane"}
    ]
    setBar(kmh, levels, "dangerLevels")
}

function setAQI(aqi) {
    const airQuality = [
        { val:1,c:"66FFFF",d:"Very good 😃" },
        { val:2,c:"00FF99",d:"Good 🙂" },
        { val:3,c:"FFFF00",d:"Fair 😐" },
        { val:4,c:"FF9900",d:"Poor 😷" },
        { val:5,c:"FF3300",d:"Hazardous ☠️" }
    ]
    setBar(aqi, airQuality, "indexOfAQI")
}
function setComponents(e) {
    getId("componentsResult").innerHTML = e.target.value
}

function setBar(target, levels, name) {
    const level = levels.find(element => element.val >= target)
    getId(name + "Description").innerHTML = level.d
    for (let i = 0; i < levels.length; i++) {
        const child = create()
        child.classList.add("dangerLevel")
        child.style.backgroundColor = "#" + levels[i].c
        child.title = levels[i].d
        if (i <= levels.indexOf(level)) {
            child.classList.add("activeLevel")
            child.style.boxShadow = "0 0 10px 2px #" + levels[i].c
        }
        getId(name).append(child)
    }
}
function setArrow(rotation) {
    const arrow = getId("arrow")
    arrow.style.setProperty("--arrowRotation", rotation + "deg")
}

function setCondition(clouds) {
    getId("sunIcon").style.setProperty("--sunPosition", (clouds*1.25) + "px")
}


function setTemp(tempJS,dataT) {
    let te = 0;
    switch (dataT) {
        case "c":
            te = tempJS
            break
        case "f":
            te = ((tempJS-32)*5)/9
            break
        case "k":
            te = tempJS-273.15
            break
    }
    const temperature = ((te*2)*-1 + 100)/1.4
    getId("Temp").style.setProperty("--tp", temperature+"%")
}

