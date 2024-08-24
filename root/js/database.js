class Product {
  constructor(id, name, imageUrl) {
      this.id = id;
      this.name = name;
      this.imageUrl = imageUrl;
  }
}

class CustomizableProduct extends Product {
  constructor(id, name, imageUrl, customizableArea) {
      super(id, name, imageUrl);
      this.customizableArea = customizableArea; // {x, y, width, height}
  }
}

class EmbroideryProduct extends CustomizableProduct {
  constructor(id, name, imageUrl, customizableArea) {
      super(id, name, imageUrl, customizableArea);
      this.appliedEmbroideries = [];
  }

  clearAllEmbroideries() {
    this.appliedEmbroideries = [];
  }

  createCustomizer(container, embroideryCatalog) {
      const productImage = new Image();
      productImage.src = this.imageUrl;
      productImage.onload = () => {
          const canvas = document.createElement('canvas');
          canvas.width = productImage.width;
          canvas.height = productImage.height;
          container.appendChild(canvas);

          const ctx = canvas.getContext('2d');

          let currentEmbroidery = null;
          let cursorX = 0;
          let cursorY = 0;

          const draw = () => {
              ctx.clearRect(0, 0, canvas.width, canvas.height);
              ctx.drawImage(productImage, 0, 0);

              // Draw applied embroideries
              this.appliedEmbroideries.forEach(emb => {
                  ctx.drawImage(emb.image, emb.x, emb.y, emb.width * emb.scale, emb.height * emb.scale);
              });

              // Draw current embroidery following cursor
              if (currentEmbroidery) {
                  ctx.drawImage(currentEmbroidery.image, 
                      cursorX - (currentEmbroidery.width * currentEmbroidery.scale) / 2, 
                      cursorY - (currentEmbroidery.height * currentEmbroidery.scale) / 2,
                      currentEmbroidery.width * currentEmbroidery.scale,
                      currentEmbroidery.height * currentEmbroidery.scale
                  );
              }
          };

          canvas.onmousemove = (e) => {
              const rect = canvas.getBoundingClientRect();
              cursorX = e.clientX - rect.left;
              cursorY = e.clientY - rect.top;
              draw();
          };

          canvas.onclick = (e) => {
              const rect = canvas.getBoundingClientRect();
              const clickX = e.clientX - rect.left;
              const clickY = e.clientY - rect.top;

              if (currentEmbroidery) {
                  // Place the embroidery
                  const x = clickX - (currentEmbroidery.width * currentEmbroidery.scale) / 2;
                  const y = clickY - (currentEmbroidery.height * currentEmbroidery.scale) / 2;

                  this.appliedEmbroideries.push({
                      image: currentEmbroidery.image,
                      x: x,
                      y: y,
                      width: currentEmbroidery.width,
                      height: currentEmbroidery.height,
                      scale: currentEmbroidery.scale
                  });

                  currentEmbroidery = null;
                  updateScaleInput(1); // Reset scale input when embroidery is placed
              } else if (this.appliedEmbroideries.length > 0) {
                  // Check if an existing embroidery was clicked
                  const lastEmbroidery = this.appliedEmbroideries[this.appliedEmbroideries.length - 1];
                  if (
                      clickX >= lastEmbroidery.x && 
                      clickX <= lastEmbroidery.x + lastEmbroidery.width * lastEmbroidery.scale &&
                      clickY >= lastEmbroidery.y && 
                      clickY <= lastEmbroidery.y + lastEmbroidery.height * lastEmbroidery.scale
                  ) {
                      // Pick up the last embroidery
                      currentEmbroidery = this.appliedEmbroideries.pop();
                      updateScaleInput(currentEmbroidery.scale);
                  }
              }
              draw();
          };

          // Create embroidery catalog
          const catalogDiv = document.createElement('div');
          catalogDiv.style.display = 'flex';
          catalogDiv.style.flexWrap = 'wrap';
          catalogDiv.style.marginTop = '10px';

          embroideryCatalog.forEach(embroideryUrl => {
              const embImg = document.createElement('img');
              embImg.src = embroideryUrl;
              embImg.style.width = '50px';
              embImg.style.height = '50px';
              embImg.style.margin = '5px';
              embImg.style.cursor = 'pointer';

              embImg.onclick = () => {
                  const newEmbroidery = new Image();
                  newEmbroidery.src = embroideryUrl;
                  newEmbroidery.onload = () => {
                      currentEmbroidery = {
                          image: newEmbroidery,
                          width: newEmbroidery.width,
                          height: newEmbroidery.height,
                          scale: 1
                      };
                      draw();
                  };
              };

              catalogDiv.appendChild(embImg);
          });

          container.appendChild(catalogDiv);

          // Create clear button
          const clearButton = document.createElement('button');
          clearButton.textContent = 'Clear All';
          clearButton.onclick = () => {
              this.clearAllEmbroideries();
              currentEmbroidery = null;
              draw();
              updateScaleInput(1); // Reset scale input when cleared
          };
          container.appendChild(clearButton);

          // Create scale controls
          const scaleLabel = document.createElement('label');
          scaleLabel.textContent = 'Scale: ';
          const scaleInput = document.createElement('input');
          scaleInput.type = 'range';
          scaleInput.min = '0.5';
          scaleInput.max = '2';
          scaleInput.step = '0.1';
          scaleInput.value = '1';
          scaleInput.oninput = () => {
              if (currentEmbroidery) {
                  currentEmbroidery.scale = parseFloat(scaleInput.value);
                  draw();
              } else if (this.appliedEmbroideries.length > 0) {
                  const lastEmbroidery = this.appliedEmbroideries[this.appliedEmbroideries.length - 1];
                  lastEmbroidery.scale = parseFloat(scaleInput.value);
                  draw();
              }
          };
          container.appendChild(scaleLabel);
          container.appendChild(scaleInput);

          const updateScaleInput = (value) => {
              scaleInput.value = value;
          };

          draw();
      };
  }
}

const embroideryCatalog = [
  '../images/d.jpg',
  '/images/embroidery2.png',
  '/images/embroidery3.png',
  // Add more embroidery designs as needed
];

const products = [
  new EmbroideryProduct(
      1,
      "Classic T-Shirt",
      "../images/OIP.jpg",
      {x: 100, y: 100, width: 200, height: 200} // Customizable area
  ),
  new EmbroideryProduct(
      2,
      "Long-Sleeved Shirt",
      "/images/longsleeved-placeholder.png",
      {x: 100, y: 100, width: 200, height: 200} // Customizable area
  )
];

function getAllProducts() {
  return products;
}

function getProductById(id) {
  return products.find(product => product.id === id);
}