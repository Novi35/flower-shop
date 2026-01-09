let userBox = document.querySelector('.header .flex .account-box');

document.querySelector('#user-btn').onclick = () =>{
    userBox.classList.toggle('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    userBox.classList.remove('active');
}

window.onscroll = () =>{
    userBox.classList.remove('active');
    navbar.classList.remove('active');
}

document.querySelectorAll('.qty-wrapper').forEach(wrapper => {
   const input = wrapper.querySelector('.qty');
   const plusBtn = wrapper.querySelector('.plus');
   const minusBtn = wrapper.querySelector('.minus');


   plusBtn.addEventListener('click', () => {
      input.value = parseInt(input.value) + 1;
   });


   minusBtn.addEventListener('click', () => {
      if (parseInt(input.value) > 1) {
         input.value = parseInt(input.value) - 1;
      }
   });
});
