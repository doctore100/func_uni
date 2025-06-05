import Glide from "@glidejs/glide"

class HeroSlider {
  constructor() {
    const allSlideshows = document.querySelectorAll(".hero-slider")

    allSlideshows.forEach((slideShow) => {
      const dotCount = slideShow.querySelectorAll(".hero-slider__slide").length

      let dotHTML = ""
      for (let i = 0; i < dotCount; i++) {
        dotHTML += `<button class="slider__bullet glide__bullet" data-glide-dir="=${i}"></button>`
      }

      slideShow.querySelector(".glide__bullets").insertAdjacentHTML("beforeend", dotHTML)

      const glide = new Glide(slideShow, {
        type: "carousel",
        perView: 1,
        autoplay: 3000
      })

      glide.mount()
    })
  }
}

export default HeroSlider
