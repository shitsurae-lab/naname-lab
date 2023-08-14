//GSAP
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

/* --- 1 --- */
//works下部
const works_list_heading = document.querySelector('.p-works--lists__heading');
if (works_list_heading) {
  works_list_heading.classList.add('js-scroll-trigger', 'js-scroll-element');
}

//SERVICE下部
const parent = document.querySelector('.p-profile--skills__container'); //配列
if (parent) {
  //SERVICE下部見出し
  parent.firstElementChild.classList.add(
    'js-scroll-trigger',
    'js-scroll-element'
  );
}

//WORKS・SERVICE下部共通
//それぞれのitemにjs-targetを付与
const [...arr] = document.querySelectorAll('.p-postList .p-postList__item');

arr.forEach((elm) => {
  elm.classList.add('js-scroll-trigger', 'js-scroll-element');
});

/* ---END 1 --- */

/* --- 2 --- */
//Service中程のアイテムにjs-target-itemsを付与
//NodeListを取得

/* --- END 2 --- */

/* --- START Function 1 --- */
const [...jsScrollElements] = document.querySelectorAll('.js-scroll-element');
const [...jsScrollTrigger] = document.querySelectorAll('.js-scroll-trigger');
const myScroll = () => {
  if (jsScrollElements) {
    jsScrollElements.forEach((elem) => {
      gsap.fromTo(
        elem,
        {
          autoAlpha: 0,
          x: 0,
          y: 8,
        },
        {
          autoAlpha: 1,
          x: 0,
          y: 0,
          duration: 1.2,
          stagger: {
            each: 0.8,
            ease: 'power4.out',
          },
          scrollTrigger: {
            trigger: elem.parentNode,
            start: 'top 80%',
          },
        }
      );
    });
  }
};
// const myScroll = () => {
//   ScrollTrigger.batch(jsScrollTarget, {
//     interval: 0.1,
//     batchMax: 1,
//     onEnter: (batch) =>
//       gsap.fromTo(
//         batch,
//         { autoAlpha: 0, x: 0, y: 8 },
//         {
//           autoAlpha: 1,
//           x: 0,
//           y: 0,
//           duration: 1.2,
//           ease: 'power4.out',
//           stagger: { each: 0.2 },
//         },
//         '-=.2'
//       ),
//     once: true,
//     start: 'top 80%',
//     markers: 'true',
//   });
//   return;
// };

/* --- END Function 1 --- */

const FuncTimeline = (key) => {
  if (key) {
    const array = gsap.utils.toArray(key);
    const tl = gsap.timeline();
    array.forEach((elem, index) => {
      if (elem) {
        if (index === 0) {
          tl.fromTo(
            elem,
            {
              autoAlpha: 0,
              x: 0,
              y: 8,
            },
            {
              autoAlpha: 1,
              x: 0,
              y: 0,
              duration: 1.2,
              stagger: {
                ease: 'power4.out',
              },
            },
            '+=3.2'
          );
        } else {
          tl.fromTo(
            elem,
            {
              autoAlpha: 0,
              x: -4,
              y: 0,
            },
            {
              autoAlpha: 1,
              x: 0,
              y: 0,
              duration: 1.2,
              stagger: {
                ease: 'power4.out',
                each: -0.4,
              },
            },
            '-=.4'
          );
        }
      }
    });
  }

  return;
};

/* - Service - */
const headlineService = document.querySelector('.p-service__heading');
const textService = document.querySelector('.p-service__text');
const mediaService = document.querySelector('.p-service__media');

//①配列(CSSはそれぞれ別)
const [...arrangement] = [headlineService, mediaService, textService];

const myTimeline = FuncTimeline;

myTimeline(arrangement);

/* -- Works -- */
const headlineWorks = document.querySelector('.p-works--intro__heading');

const textWorks = document.querySelector('.p-works--intro__text');

const mediaWorks = document.querySelector('.p-works--intro__media');

const [...arrangement2] = [headlineWorks, mediaWorks, textWorks];

const myTimeline2 = FuncTimeline;

myTimeline2(arrangement2);

// export default myTimeline;
export { myScroll, myTimeline, myTimeline2 };
