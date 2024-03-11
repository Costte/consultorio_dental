const body = document.querySelector('body'),
      sidebar = body.querySelector('.lateral'),
      toggle = body.querySelector(".toggles"),
      searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");

toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
})

searchBtn.addEventListener("cick", () => {
    sidebar.classList.remove("close");
})

modeSwitch.addEventListener("click", () => {

    body.classList.toggle("dark");
    if (body.classList.contains("dark")){
        modeText.innerText = "Modo Light"

    } else{
        modeText.innerText = "Modo Oscuro"
    }
})