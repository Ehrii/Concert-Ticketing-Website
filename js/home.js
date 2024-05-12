let slides = document.querySelectorAll('.slide-container');
let index = 0;
function next() {
  slides[index].classList.remove('active');
  index = (index + 1) % slides.length;
  slides[index].classList.add('active');
}
function prev() {
  slides[index].classList.remove('active');
  index = (index - 1 + slides.length) % slides.length;
  slides[index].classList.add('active');
}

// var container = document.getElementById('personcontainer')
//       var slider = document.getElementById('slider');
//       var personslides = document.getElementsByClassName('personslide').length;
//       var buttons = document.getElementsByClassName('btnperson');
//       console.log(buttons);
//       var currentPosition = 0;
//       var currentMargin = 0;
//       var slidesPerPage = 0;
//       var slidesCount = personslides - slidesPerPage;
//       var containerWidth = container.offsetWidth;
//       var prevKeyActive = false;
//       var nextKeyActive = true;

//       window.addEventListener("resize", checkWidth);

//       function checkWidth() {
//         containerWidth = container.offsetWidth;
//         setParams(containerWidth);
//       }

//       function setParams(w) {
//         if (w < 551) {
//           slidesPerPage =1;
//         } else {
//           if (w < 901) {
//             slidesPerPage = 3;
//           } else {
//             if (w < 1101) {
//               slidesPerPage = 3;
//             } else {
//               slidesPerPage = 3;
//             }
//           }
//         }
//         slidesCount = personslides - slidesPerPage;
//         if (currentPosition > slidesCount) {
//           currentPosition -= slidesPerPage;
//         };
//         currentMargin = - currentPosition * (100 / slidesPerPage);
//         slider.style.marginLeft = currentMargin + '%';
//         if (currentPosition > 0) {
//           buttons[0].classList.remove('inactive');
//         }
//         if (currentPosition < slidesCount) {
//           buttons[1].classList.remove('inactive');
//         }
//         if (currentPosition >= slidesCount) {
//           buttons[1].classList.add('inactive');
//         }
//       }

//       setParams();

//       function slideRight() {
//         if (currentPosition != 0) {
//           slider.style.marginLeft = currentMargin + (100 / slidesPerPage) + '%';
//           currentMargin += (100 / slidesPerPage);
//           currentPosition--;
//         };
//         if (currentPosition === 0) {
//           buttons[0].classList.add('inactive');
//         }
//         if (currentPosition < slidesCount) {
//           buttons[1].classList.remove('inactive');
//         }
//       };

//       function slideLeft() {
//         if (currentPosition != slidesCount) {
//           slider.style.marginLeft = currentMargin - (100 / slidesPerPage) + '%';
//           currentMargin -= (100 / slidesPerPage);
//           currentPosition++;
//         };
//         if (currentPosition == slidesCount) {
//           buttons[1].classList.add('inactive');
//         }
//         if (currentPosition > 0) {
//           buttons[0].classList.remove('inactive');
//         }
//       };

//SWIPER
      var swiper = new Swiper(".mySwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
          rotate: 50,
          stretch: 0,
          depth: 100,
          modifier: 1,
          slideShadows: true,
        },
        loop: true,
        autoplay: {
          delay: 2000,
          disableOnInteraction: false,
        }

      });