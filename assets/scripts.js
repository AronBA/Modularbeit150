function getId(id){return document.getElementById(id)}
function   setSun(time, sunrise, sunset) {
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
    const levels = [{min:0,max:2,c:"66FFFF",d:"Calm"},{min:3,max:5,c:"00FFFF",d:"Light air"},{min:6,max:11,c:"00FF99",d:"Light breeze"},
        {min:12,max:19,c:"00FF99",d:"Gentle breeze"},{min:20,max:29,c:"66FF66",d:"Moderate breeze"},{min:30,max:39,c:"99FF33",d:"Fresh breeze"},
        {min:40,max:50,c:"CCFF33",d:"Strong breeze"},{min:51,max:61,c:"FFFF00",d:"Moderate gale"},{min:62,max:74,c:"FFC000",d:"Fresh gale"},
        {min:75,max:87,c:"FF9900",d:"Strong gale"},{min:88,max:101,c:"FF6600",d:"Whole gale"},{min:102,max:116,c:"FF3300",d:"Violent storm"},
        {min:117,max:9999,c:"FF0000",d:"Hurricane"}]
    setBar(kmh, levels, "dangerLevels")
}

function setAQI(aqi) {
    const airQuality = [
        {
            min:1,max:1,c:"66FFFF",d:"😃"
        },
        {
            min:2,max:2,c:"00FF99",d:"🙂"
        },
        {
            min:3,max:3,c:"FFFF00",d:"😐"
        },
        {
            min:4,max:3,c:"FF9900",d:"😷"
        },
        {
            min:4,max:3,c:"FF3300",d:"☠️"
        }
    ]
    setBar(aqi, airQuality, "indexOfAQI")
}

function setBar(target, levels, name) {
    const level = levels.find(element => target >= element.min && target <= element.max)
    const description = getId(name + "Description")
    description.innerHTML = level.d
    const dangerLevels = getId(name)
    for (let i = 0; i < levels.length; i++) {
        const child = create()
        child.classList.add("dangerLevel")
        child.style.backgroundColor = "#" + levels[i].c
        child.title = levels[i].d
        if (i <= levels.indexOf(level)) {
            child.classList.add("activeLevel")
            child.style.boxShadow = "0 0 10px 2px #" + levels[i].c
        }
        dangerLevels.append(child)
    }
}
function setArrow(rotation) {
    const arrow = getId("arrow")
    arrow.style.setProperty("--arrowRotation", rotation + "deg")
}

let i = 0;

function thermo() {
    if (i == 0) {
        i = 1;
        const elem = document.getElementById("block");
        let height = 1;
        const id = setInterval(frame, 10);

        function frame() {
            if (height >= 100) {
                clearInterval(id);
                i = 0;
            } else {
                height++;
                elem.style.width = height + "%";
            }
        }
    }
}

function setCondition(clouds) {
    console.log(clouds)
    getId("sunIcon").style.setProperty("--sunPosition", (clouds*1.25) + "px")
}
function setTemp(tempJS) {
    // input = -20 - 50
    // output = 100 - 0
    const temperature = ((tempJS*2)*-1 + 100)/1.4
    // -40 - 100
    getId("Temp").style.setProperty("--tp", temperature+"%")
}
function  setTempBar(Temp, pro, name){
    const proc = pro.find(element => Temp >= element.min && Temp <= element.max)

}
