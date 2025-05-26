function addJSTarget() {
  const targets = document.querySelectorAll(
    '.p-front__content .ark-block-section__bodyInner'
  );

  targets.forEach((target) => target.classList.add('js-target'));
}

//パスワード保護の場合、post-password-requiredがarticleに付与されるので親要素のmainにclassをプラス
const passwordClass = () => {
  document.addEventListener('DOMContentLoaded', function () {
    const protectedArticle = document.querySelector(
      'article.post-password-required'
    );
    if (protectedArticle) {
      protectedArticle.closest('main').classList.add('has-password-form');
    }
  });
};
const addSelector = () => {
  addJSTarget();
  passwordClass();
};
export default addSelector;
