let model, prediction, image;
const chooseFiles = document.getElementById('chooseFiles');
const modelNameSelect = document.getElementById("modelNameSelect");
const segmentImageButton = document.getElementById("segmentImage");
const removeSelectedObjectsButton = document.getElementById("removeSelectedObjects");
const restoreSelectedObjectsButton = document.getElementById("restoreSelectedObjects");
const loadModelButton = document.getElementById("loadModel");
const legendsDiv = document.getElementById("legends");
const imageWrapperDiv = document.getElementById("imgWrapper");
const legendLabel = document.getElementById("legendLabel");
const removeOrRestoreSelectedObjectsLabel = document.getElementById("removeOrRestoreSelectedObjectsLabel");

// File Input onchange event handler to handle the files uploaded by the user
chooseFiles.onchange = () => {
    const [file] = chooseFiles.files;
    if (file) {
        while(imageWrapperDiv.firstChild) {
            imageWrapperDiv.removeChild(imageWrapperDiv.firstChild);
        }

        image = new Image();
        const imgURL = URL.createObjectURL(file);

        image.onload = () => {
            // Set image dimensions to the original dimensions of the loaded image
            image.width = "500";

            imageWrapperDiv.appendChild(image);
            legendLabel.style.visibility = "hidden";
            legendsDiv.style.visibility = "hidden";
            removeOrRestoreSelectedObjectsLabel.style.visibility = "hidden";
            removeSelectedObjectsButton.style.visibility = "hidden";
            restoreSelectedObjectsButton.style.visibility = "hidden";
        };

        image.src = imgURL;
    }
};

// Invoke predict() function on click of "Segment Image" button
segmentImageButton.onclick = predict;

// Invoke removeOrRestoreSelectedObjects() function on click of "Remove Selected Objects" button
removeSelectedObjectsButton.onclick = removeOrRestoreSelectedObjects;

// Invoke removeOrRestoreSelectedObjects() function on click of "Restore Selected Objects" button
restoreSelectedObjectsButton.onclick = removeOrRestoreSelectedObjects;

// Inline async function to be executed on click of "Load Model" button
loadModelButton.onclick = async () => {
    // Disable the "Segment Image" button as the model is not yet loaded
    segmentImageButton.disabled = true;
    updateModelLoadStatus("Model Loading...");

    // Get the selected model from the model's dropdown on the UI
    const modelName = modelNameSelect.options[modelNameSelect.selectedIndex].value;
    
    // Invoke async loadModel() function to load the model selected by user
    await loadModel(modelName);
    updateModelLoadStatus(modelName + " model loaded!");

    // Enable the "Segment Image" button
    segmentImageButton.disabled = false;
};

// Function to load the deeplab model based on user selection
async function loadModel(modelName) {
    model = await deeplab.load({ "base": modelName, "quantizationBytes": 2 });
}

function updateModelLoadStatus(status) {
    document.getElementById("modelLoadedStatus").innerHTML = status;
}

// Function to perform the semantic image segmentation
async function predict() {
    // Perform the inference (segmentation) by passing the image to the model.segment() function
    prediction = await model.segment(image);

    // Pass the prediction output to renderPrediction() method to display the segmented image
    renderPrediction(prediction);
}

// Function to display the segmented image using the model's prediction output
function renderPrediction(prediction) {
    const { legend, height, width, segmentationMap } = prediction;
    const segmentationMapData = new ImageData(segmentationMap, width, height);
    console.log(width);

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    canvas.width = width;
    canvas.height = height;

    ctx.putImageData(segmentationMapData, 0, 0);

    // Adjust image dimensions to match the segmented output
    image.width = width;
    image.height = height;

    displayCanvas(canvas);
    displayLegends(legend);
}

// Function to remove or restore objects from the image based on user selection
function removeOrRestoreSelectedObjects(e) {
    let target = (e.target) ? e.target : e.srcElement;
    const { legend, height, width, segmentationMap } = prediction;

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = width;
    canvas.height = height;
    ctx.drawImage(image, 0, 0, width, height);

    const imgData = ctx.getImageData(0, 0, width, height);
    const alphaValueToSet = (target.id == 'removeSelectedObjects') ? 0 : 255;

    for(let i = 0; i < segmentationMap.length; i += 4) {
        Object.keys(objectColors).forEach((objectColor) => {
            let color = objectColors[objectColor];
            if(segmentationMap[i] == color[0] && segmentationMap[i+1] == color[1] && segmentationMap[i+2] == color[2]) {
                imgData.data[i+3] = alphaValueToSet;
            }
        });
    }

    ctx.putImageData(imgData, 0, 0);
    displayCanvas(canvas);
}

// Function to display the canvas
function displayCanvas(canvas) {
    if(imageWrapperDiv.childNodes.length > 1) {
        imageWrapperDiv.removeChild(imageWrapperDiv.childNodes[1]);
    }
    imageWrapperDiv.appendChild(canvas);
}

// Function to display the legends data from the prediction output
function displayLegends(legendObj) {
    while(legendsDiv.firstChild) {
        legendsDiv.removeChild(legendsDiv.firstChild);
    }

    Object.keys(legendObj).forEach((legend) => {
        const [red, green, blue] = legendObj[legend];

        const span = document.createElement('span');
        span.innerHTML = legend;
        span.style.backgroundColor = `rgb(${red}, ${green}, ${blue})`;
        span.style.padding = '10px';
        span.style.marginRight = '10px';
        span.style.color = '#ffffff';
        span.onclick = storeObjectColor;

        legendsDiv.appendChild(span);
    });

    legendLabel.style.visibility = "visible";
    legendsDiv.style.visibility = "visible";
    removeOrRestoreSelectedObjectsLabel.style.visibility = "visible";
    removeSelectedObjectsButton.style.visibility = "visible";
    restoreSelectedObjectsButton.style.visibility = "visible";  
}

// Object to hold the color of each legend
let objectColors = {};

// Function to store the color for each selected object
function storeObjectColor(e) {
    let target = (e.target) ? e.target : e.srcElement;
    
    let objectName = target.textContent;
    let objectColor = window.getComputedStyle(target).backgroundColor;
    objectColor = objectColor.replace('rgb(', '').replace(')', '').split(',').map(Number);
    objectColors[objectName] = objectColor;

    target.style.border = "5px solid green";
}
