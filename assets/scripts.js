const urlDay = "../assets/sunrise/day.png"
const urlNight = "../assets/sunrise/night.png"

function getId(id){return document.getElementById(id)}
function setSun(time, sunrise, sunset) {
    const background = getId("sunWeather").style
    let day = true
    if (time < sunrise || time > sunset) day = false
    const url = day ? urlDay : urlNight
    const textColor = day ? "black" : "white"
    background.setProperty("--textColor", textColor)
    background.setProperty("--backgroundUrl", "url(" + url + ")")
}
function create(tag = "div") {return document.createElement(tag)}

function getHour(time) {
    return new Date(time).getHours()
}
function setProgress(time = 28201000) {
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