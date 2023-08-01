const CreateSVG = () => {
  const name = 'http://www.w3.org/2000/svg';
  const elemSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  elemSVG.setAttribute('id', 'layer01');
  elemSVG.setAttribute('viewBox', '0 0 1920 1080');
  const elemDefs = document.createElementNS(name, 'defs');
  elemSVG.appendChild(elemDefs);
  const elemStyle = document.createElementNS(name, 'style');
  elemDefs.appendChild(elemStyle);

  const styleText = `cls-1{stroke:#000;stroke-miterlimit:10;stroke-width:2px;}`;
  elemStyle.innerHTML = styleText;

  const cls = 'cls-1';
  const elemDoc = document.createElementNS(name, 'path');
  const pt01 = elemDoc;
  const pt02 = elemDoc;
  pt01.setAttribute('class', cls);
  pt02.setAttribute('class', cls);
  pt01.setAttribute(
    'd',
    'm797.21,382.6c3.2-.64,7.2-1.44,10.88-1.44,12.32,0,16.32,4.48,16.32,9.28,0,2.72-1.92,6.24-5.6,6.24-2.08,0-3.84-2.08-3.84-4.16,0-2.88-3.68-4.96-6.88-4.96-4.48,0-9.6,1.6-15.04,3.36-15.52,13.76-36,60.16-36,78.72,0,1.6.8,3.04.8,4.96,0,2.24-1.76,4.96-4.8,4.96-2.08,0-4.8-1.28-4.8-5.76,0-22.88,17.92-59.2,30.56-78.88-7.2,2.24-15.68,4.64-24,4.64-4.48,0-15.68-1.92-15.68-8.8,0-2.56,2.08-4.48,4.64-4.48,1.44,0,3.04.8,3.84,1.92,1.76,2.24,4,3.84,7.36,3.84,11.04,0,20.96-3.36,31.36-6.88,2.72-2.88,5.44-4.8,7.04-4.8,2.24,0,3.04,1.44,3.84,2.24Z'
  );
  pt02.setAttribute(
    'd',
    'm809.05,435.4c0,1.12.48,4.8,2.08,4.8,4,0,6.72-1.12,9.6-2.4,1.44-.64,3.68-2.08,4.64-2.08,1.92,0,3.04.8,3.04,2.88,0,1.92-2.4,4-7.68,6.56-5.12,2.4-11.68,2.72-13.28,2.72-3.68,9.44-10.88,21.76-22.24,21.76-4.8,0-9.44-3.68-9.44-10.88,0-15.04,13.44-29.12,24.16-29.12,6.24,0,9.12,3.04,9.12,5.76Zm-10.88,5.76c0-.96.32-2.24.64-3.2-6.4.32-15.2,11.36-15.2,21.12,0,2.4,1.76,4.16,3.36,4.16,5.92,0,13.76-14.24,13.76-18.24-.96-.16-2.56-2.08-2.56-3.84Z'
  );
  elemSVG.appendChild(pt01);
  elemSVG.appendChild(pt02);

  //console.log(elemSVG);
};
export default CreateSVG;
