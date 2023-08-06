//GSAP
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

/* --- 1 --- */
const works_list_heading = document.querySelector('.p-works--lists__heading');
if (works_list_heading) {
  works_list_heading.classList.add('js-target');
}

//works下部、それぞれのitemにjs-targetを付与
const [...arr] = document.querySelectorAll('.p-postList .p-postList__item');

arr.forEach((elm) => {
  elm.classList.add('js-target');
});

// const [...arrs] = document.querySelectorAll(
//   '.p-profile--skills__heading__inner'
// );

// arrs.forEach((elm) => {
//   elm.classList.add('js-target');
// });

const parent = document.querySelector('.p-profile--skills__container'); //配列
if (parent) {
  parent.firstElementChild.classList.add('js-target');
}

// if (parent) {
//   parent.forEach((elm) => {
//     console.log(elm);
//     elm.firstElementChild.classList.add('js-target');
//   });
// }

/* ---END 1 --- */

/* --- 2 --- */
//Service中程のアイテムにjs-target-itemsを付与
//NodeListを取得
const columns = document.querySelector(
  '.p-service__columns >.ark-block-columns__inner'
);

if (columns) {
  const [...array] = columns.children;
  array.forEach((elem) => {
    elem.classList.add('js-target');
  });
}

/* --- END 2 --- */

// const items = columnsInner[0].children;
// console.log(items);

// items.forEach((item) => {
//   item.classList.add('js-target');
//   console.log(item);
// });

const FuncScrollBatch = (element, num = 3, text = 'スクロールトリガー') => {
  // console.log(`${text}読みこんでます`);
  const batch = ScrollTrigger.batch(element, {
    // interval: 1,
    batchMax: num,
    onEnter: (batch) =>
      gsap.fromTo(
        batch,
        { autoAlpha: 0, x: 0, y: 8 },
        {
          autoAlpha: 1,
          x: 0,
          y: 0,
          duration: 1.2,
          ease: 'power4.out',
          stagger: 0.2,
        },
        '-=.4'
      ),
    once: true,
  });
  return batch;
};

const myScroll = FuncScrollBatch;
const jsTarget = document.querySelector('.js-target');

myScroll(jsTarget);

const myScroll2 = FuncScrollBatch;
const jsTargetItem = document.querySelector('.js-target');
myScroll2(jsTargetItem);

// const FuncTimeline = (headline, media, text) => {
//   const array = [headline, media, text];
//   const tl = gsap.timeline();
//   array.forEach((elem, index) => {
//     if (index === 0) {
//       tl.fromTo(
//         elem,
//         {
//           autoAlpha: 0,
//           y: 8,
//         },
//         {
//           autoAlpha: 1,
//           y: 0,
//           duration: 1.2,
//           ease: 'power4.out',
//         },
//         '+=3.2'
//       );
//     } else {
//       tl.fromTo(
//         elem,
//         {
//           autoAlpha: 0,
//           x: -8,
//         },
//         {
//           autoAlpha: 1,
//           x: 0,
//           duration: 1.2,
//           ease: 'power4.out',
//         },
//         '-=.8'
//       );
//     }
//   });

//   return array;
// };
const FuncTimeline = (key) => {
  if (key) {
  }
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
export { myScroll, myScroll2, myTimeline, myTimeline2 };
