let prevContainer = document.querySelector('.product-prev');
let prevBox = prevContainer.querySelectorAll('.prev');

document.querySelector('.product-container').forEach(product =>{
    product.onclick = () =>{
        prevContainer.style.display = 'flex';
        let name = product.getAttribute('data-name');
        prevBox.forEach(prev=>{
            let target = prev.getAttribute('data-target');
            if(name == target)
            {
                prev.classList.add('active');
            }
        });
    };
});

prevBox.forEach(close =>{
    close.querySelector('.fa-times').onclick = () =>{
        close.classList.remove('active');
        prev.classList.remove('active');
        prevContainer.style.display = 'none';
    };
});