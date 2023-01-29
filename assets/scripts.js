const urlDay = "../assets/sunrise/day.png"
const urlNight = "../assets/sunrise/night.png"
function setSun(time, sunrise, sunset) {
    const background = document.getElementById("sunWeather").style
    let day = true
    if (time < sunrise || time > sunset) day = false
    const url = day ? urlDay : urlNight
    const textColor = day ? "black" : "white"
    background.setProperty("--textColor", textColor)
    background.setProperty("--backgroundUrl", "url(" + url + ")")
}

//setSun(1675000484, 1675019638, 1675069349)
// Sidney = 1675000484, 1675019638, 1675069349