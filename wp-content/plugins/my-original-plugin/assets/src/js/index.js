import '../css/style.scss';

import gnav from './gnav';
gnav();

//オープニングアニメーション
import openingAnime from './openingAnime';
openingAnime();

import headerAnimation from './headerAnimation';
headerAnimation();

//-- addSelector
import addSelector from './addSelector';
addSelector();

//Intersection Observe
import intersection from './intersection';
intersection();

//-- START GSAP & ScrollTrigger

import {
  myScroll,
  myScroll2,
  myTimeline,
  myTimeline2,
} from './myScrollTrigger';

myTimeline();
myTimeline2();
myScroll();
myScroll2();

const FuncTime = (hoge = 10, fuga = 20, piyo = 30) => {
  const arr = [hoge, fuga, piyo];
  if (arr.length) {
    console.log('arrはtrue');
  }
  arr.forEach((elm) => {
    console.log(elm);
  });
  return arr;
};

const test = FuncTime; //関数を代入するから文末は();ではなく、;にする
test(); //関数が代入された変数を実行するので文末は();でOK

import CreateSVG from './createSVG';
CreateSVG();

import CreateWave from './CreateWave';
CreateWave();

//--- START: Swiper ---
import {
  swiperMini,
  swiperGallery,
  heroSwiper,
  indexSwiper,
  index_hero_slider,
  infinite_loopSlider,
  postSwiper,
} from './myswiper';

//-- myswiperは関数が指定されているわけではないので()による関数の実行はしない

//-- START summary・detailアニメーション
import accordionAnim from './accordion';
accordionAnim();
//--accordionAnimの中にクリックイベントが記載されているので、accordionAnim();(->関数実行)を行う

import particles from './particles';
particles();

//--Font awesome
import '@fortawesome/fontawesome-free/js/fontawesome';
import '@fortawesome/fontawesome-free/js/solid';
import '@fortawesome/fontawesome-free/js/regular';
