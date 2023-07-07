//GSAP
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

const myScrollTrigger = () => {
  console.log('myScrollTrigger、読みこめてるよ');
  // if ('.js-scroll-target') {
  //   gsap.set('.js-scroll-target', { autoAlpha: 0, y: 20, scale: 0.96 });
  //   gsap.to('.js-scroll-target', {
  //     autoAlpha: 1,
  //     y: 0,
  //     scale: 1,
  //     duration: 1.2,
  //     scrollTrigger: {
  //       trigger: '.js-scroll-trigger',
  //       start: 'top bottom-=30%',
  //       markers: true,
  //     },
  //     stagger: 0.8,
  //   });
  // }
};

const myTimeline = () => {
  const jsHeading = document.querySelector(
    '.p-page__strengths--intro__heading'
  );
  const jsText = document.querySelector('.p-page__strengths--intro__text');
  const jsMedia = document.querySelector('.p-page__strengths--intro__media');
  // 指定した要素をイニシャライズ
  gsap.set([jsHeading, jsText, jsMedia], {
    autoAlpha: 0,
    x: -100,
  });

  //タイムラインを作成
  const tl = gsap.timeline();
  tl.to(jsHeading, { autoAlpha: 1, x: 0, scale: 1, duration: 0.8, delay: 2.8 })
    .to(jsMedia, {
      autoAlpha: 1,
      x: 0,
      duration: 1.2,
      delay: 0,
    })
    .to(jsText, {
      autoAlpha: 1,
      x: 0,
      duration: 1.6,
      delay: 0,
    });

  // const [...tlElem] = document.querySelectorAll('.tl-elem');
  // tlElem.forEach((element) => {
  //   // elementをイニシャライズ
  //   gsap.set(element, {
  //     autoAlpha: 0,
  //   });
  //   //タイムラインを作成
  //   const tl = gsap.timeline();
  //   tl.to(element, {
  //     autoAlpha: 1,
  //     delay: 3,
  //     stagger: {
  //       from: 'start',
  //       each: 1.6,
  //     },
  //   });
  //   // console.log(element);
  // });

  console.log('myTimelineも読み込まれてます');
};

export default myTimeline;
