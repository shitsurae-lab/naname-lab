// myScrollTrigger.js
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/* ----------------------------
   DOMにclassを付与する初期処理
----------------------------- */
const addScrollTriggerClasses = () => {
  const worksHeading = document.querySelector('.p-works--lists__heading');
  const serviceParent = document.querySelector('.p-profile--skills__container');
  const postItems = document.querySelectorAll('.p-postList .p-postList__item');

  if (worksHeading) {
    worksHeading.classList.add('js-scroll-trigger', 'js-scroll-element');
  }

  if (serviceParent && serviceParent.firstElementChild) {
    serviceParent.firstElementChild.classList.add(
      'js-scroll-trigger',
      'js-scroll-element'
    );
  }

  postItems.forEach((item) => {
    item.classList.add('js-scroll-trigger', 'js-scroll-element');
  });
};

/* ----------------------------
   スクロールで出現アニメーション
----------------------------- */
const myScroll = () => {
  const elements = document.querySelectorAll('.js-scroll-element');
  elements.forEach((elem) => {
    gsap.fromTo(
      elem,
      {
        autoAlpha: 0,
        y: 8,
      },
      {
        autoAlpha: 1,
        y: 0,
        duration: 1.2,
        ease: 'power4.out',
        scrollTrigger: {
          trigger: elem.parentNode,
          start: 'top 80%',
        },
      }
    );
  });
};

/* ----------------------------
   タイムライン用の共通関数
----------------------------- */
const createTimelineAnimation = (elements) => {
  const tl = gsap.timeline();
  elements.forEach((elem, index) => {
    if (!elem) return;

    const fromProps = { autoAlpha: 0 };
    const toProps = {
      autoAlpha: 1,
      duration: 1.2,
      ease: 'power4.out',
    };

    if (index === 0) {
      fromProps.y = 8;
      toProps.y = 0;
      tl.fromTo(elem, fromProps, toProps, '+=3.2');
    } else {
      fromProps.x = -4;
      toProps.x = 0;
      tl.fromTo(elem, fromProps, toProps, '-=0.4');
    }
  });

  return tl;
};

/* ----------------------------
   Timelineアニメーション（Service）
----------------------------- */
const myTimeline = () => {
  const elements = [
    document.querySelector('.p-service__heading'),
    document.querySelector('.p-service__media'),
    document.querySelector('.p-service__text'),
  ];
  createTimelineAnimation(elements);
};

/* ----------------------------
   Timelineアニメーション（Works）
----------------------------- */
const myTimeline2 = () => {
  const elements = [
    document.querySelector('.p-works--intro__heading'),
    document.querySelector('.p-works--intro__media'),
    document.querySelector('.p-works--intro__text'),
  ];
  createTimelineAnimation(elements);
};

/* ----------------------------
   data-section ピン留めアニメーション
----------------------------- */
const sectionPinAnimation = () => {
  const sections = document.querySelectorAll('[data-section]');

  sections.forEach((section) => {
    const inner = section.querySelector('[data-section-inner]');
    const image = section.querySelector('.section__image');
    const text = section.querySelector('.section__txt');

    gsap.set([image, text], { opacity: 0, yPercent: 20 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top top',
        end: 'bottom top',
        pin: true,
        scrub: true,
        // markers: true,
      },
    });

    if (section.classList.contains('js-appearance')) {
      tl.to(image, {
        opacity: 1,
        yPercent: 0,
        ease: 'power2.out',
        duration: 0.5,
      });
      tl.to(
        text,
        { opacity: 1, yPercent: 0, ease: 'power2.out', duration: 0.5 },
        '-=0.3'
      );
    } else {
      tl.to(text, {
        opacity: 1,
        yPercent: 0,
        ease: 'power2.out',
        duration: 0.5,
      });
      tl.to(
        image,
        { opacity: 1, yPercent: 0, ease: 'power2.out', duration: 0.5 },
        '-=0.3'
      );
    }
  });
};

const asidePinAnimation = () => {
  const selectors = [
    '.single-achievement .l-main',
    '.tax-achievement_cat .l-main',
    '.tax-achievement_tag .l-main',
  ];
  selectors.forEach((selector) => {
    const mainContent = document.querySelector(selector);
    const aside = document.querySelector('.l-sidebar');
    if (mainContent && aside) {
      ScrollTrigger.create({
        trigger: mainContent,
        start: 'top top',
        end: 'bottom bottom',
        pin: aside,
        pinSpacing: false,
        // markers: true, // ← デバッグ用
      });
    }
  });
};

const adaptHeight = () => {
  const heading = document.querySelector('.p-index-section__heading');
  const sub = document.querySelector('.p-index-section__heading--sub');
  if (!sub) {
    console.warn('要素 .p-index-section__heading--sub が見つからないよ！');
    return;
  }
  const height = sub.offsetTop + sub.offsetHeight;
  console.log(`高さは${height}です`);
  heading.style.setProperty('--heading-height', `${height}px`);
};

/* ----------------------------
   初期化用関数
----------------------------- */
const initScrollAnimations = () => {
  addScrollTriggerClasses();
  sectionPinAnimation();
  asidePinAnimation();
  adaptHeight();
};

export { initScrollAnimations, myScroll, myTimeline, myTimeline2 };

/****
 ** スクロールに応じてn+1番目のセクションがn番目のセクションに重なる
 ****/
const sections = document.querySelectorAll('[data-section]');

sections.forEach((section) => {
  const inner = section.querySelector('[data-section-inner]');
  const image = section.querySelector('.p-index-section__image img');
  const imageWrapper = section.querySelector('.p-index-section__image');
  const text = section.querySelector('.p-index-section__txt');

  // ✅️1-1スクロールをすると、要素が順にフェードインする
  //   gsap.set([image, text], { opacity: 0, yPercent: 20 });

  //   const tl = gsap.timeline({
  //     scrollTrigger: {
  //       trigger: section,
  //       start: "top top",
  //       end: "bottom top",
  //       pin: true,
  //       scrub: true,
  //       markers: true,
  //     },
  //   });

  //   if (section.classList.contains("js-appearance")) {
  //     tl.to(image, { opacity: 1, yPercent: 0, ease: "power2.out", duration: 0.5 });
  //     tl.to(text, { opacity: 1, yPercent: 0, ease: "power2.out", duration: 0.5 }, "-=0.3");
  //   } else {
  //     tl.to(text, { opacity: 1, yPercent: 0, ease: "power2.out", duration: 0.5 });
  //     tl.to(image, { opacity: 1, yPercent: 0, ease: "power2.out", duration: 0.5 }, "-=0.3");
  //   }

  //✅️ 2 sectionが重なってゆくもの
  ScrollTrigger.create({
    // markers: 'true',
    trigger: section,
    start: 'bottom bottom',
    onEnter: () => {
      gsap.set(inner, {
        position: 'fixed',
        bottom: 0,
      });
    },
    onLeaveBack: () => {
      gsap.set(inner, {
        position: 'absolute',
        bottom: 'auto',
      });
    },
  });
  // +1, +2 どちらかを選択（両方はNG）
  // ✅ 2 +1 画像に拡大スクロールアニメーションを追加
  gsap.to(image, {
    scale: 1.8,
    ease: 'none',
    scrollTrigger: {
      trigger: section,
      start: 'top bottom', // sectionが画面に入り始めたら
      end: 'bottom top', // sectionが画面から出るまで
      scrub: true, // スクロールに連動
      // markers: true          // デバッグ用（必要なら）
    },
  });

  // ✅️ 2 +2
  // ScrollTrigger.create({
  //   trigger: section,
  //   start: "top bottom",
  //   end: "bottom top",
  //   scrub: true,
  //   // markers: true,
  //   onUpdate: (self) => {
  //     // スクロール進行度（0〜1）
  //     const progress = self.progress;

  //     // スクロールの進行度に応じて scale を更新
  //     gsap.to(image, {
  //       scale: 1 + 0.8 * progress, // 1 → 1.8
  //       overwrite: "auto", // 上書きOKにして滑らかに
  //       ease: "none",
  //       duration: 0.01 // 即時反映
  //     });
  //   }
  // });
});
