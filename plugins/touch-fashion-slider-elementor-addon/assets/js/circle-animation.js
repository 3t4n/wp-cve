
    const radius = 0;
    const diameter = radius * 1;

    const circle = Array.from(document.querySelectorAll('.logo_circle .cir-text p')),
          totalcircle = circle.length;

    circle.forEach((circle) => {

        circle.style.width = `${diameter}px`;
        circle.style.height = `${diameter}px`;

        const text = circle.innerText;
        const characters = text.split("");
        circle.innerText = null;

        const startAngle = -90;
        const endAngle = 270;
        const angleRange = endAngle - startAngle;

        const deltaAngle = angleRange / characters.length;
        let currentAngle = startAngle;

        characters.forEach((char, index) => {
          const charElement = document.createElement("span");
          charElement.innerText = char;

          const rotate = `rotate(${index * deltaAngle}deg)`;
          charElement.style.transform = `${rotate}`;

          currentAngle += deltaAngle;
          circle.appendChild(charElement);
        });

    })