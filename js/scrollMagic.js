document.addEventListener("DOMContentLoaded", function() {
    // Inicialize o ScrollMagic Controller
    var controller = new ScrollMagic.Controller();
  
    // Selecione todos os elementos com a classe .fade-in
    var elements = document.querySelectorAll(".fade-in");
  
    // Loop através de cada elemento e crie uma cena para ele
    elements.forEach(function(element) {
      var scene = new ScrollMagic.Scene({
        triggerElement: element,  // O elemento que irá disparar a animação
        triggerHook: 0.8,        // A posição em que a cena é acionada (80% da altura da janela)
        reverse: false           // Impedir que a animação seja revertida ao rolar para cima
      })
      .addTo(controller);        // Adicionar a cena ao controlador
  
      // Defina a animação usando GSAP (neste caso, um fade-in)
      var tween = gsap.fromTo(element, { opacity: 0 }, { opacity: 1, duration: 1 });
  
      // Use o evento 'enter' do ScrollMagic para acionar a animação
      scene.on("enter", function() {
        tween.play(); // Inicia a animação do GSAP quando a cena entra na viewport
      });
  
      // Use o evento 'leave' do ScrollMagic se desejar reverter a animação ao sair
      scene.on("leave", function() {
        tween.reverse(); // Reverte a animação do GSAP ao sair da viewport
      });
    });
  });
  