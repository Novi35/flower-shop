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

document.addEventListener('click', function(e){

  if(e.target.classList.contains('quick-plus') || 
     e.target.classList.contains('quick-minus')){

    const wrapper = e.target.closest('.quick-qty');
    const input = wrapper.querySelector('.quick-qty-input');
    let value = parseInt(input.value);

    if(e.target.classList.contains('quick-plus')){
      value++;
    }

    if(e.target.classList.contains('quick-minus') && value > 1){
      value--;
    }

    input.value = value;
  }

});
