window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            const img = document.querySelector('.imagemLogo');

            if (window.scrollY > 50) {
                navbar.classList.add('transparent');
                img.classList.add('imgMenor')
            } else {
                navbar.classList.remove('transparent');
                img.classList.remove('imgMenor');
            }
        });




