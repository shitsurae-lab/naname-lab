import '../css/style.scss';

import gnav from './gnav';
gnav();

//オープニングアニメーション
import openingAnime from './openingAnime';
openingAnime();

//-- addSelector
import addSelector from './addSelector';
addSelector();

//Intersection Observe
import intersection from './intersection';
intersection();

//-- START GSAP & ScrollTrigger
// import gsap from 'gsap';
// import ScrollTrigger from 'gsap/ScrollTrigger';
// gsap.registerPlugin(ScrollTrigger);
// import custom_gsap from './custom_gsap';
// custom_gsap();

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
