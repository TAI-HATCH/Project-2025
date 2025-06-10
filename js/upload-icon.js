// Script to create name for selected svg-file:

// Function to form the name for svg-icon based on the language's name:
function createNameForSvgIcon(element_id) {
  let inputElement = document.getElementById(element_id); // define the input field
  let normalizedInputValue = inputElement.value
    .toLowerCase()
    .replaceAll(" ", "-"); //Normalize the name: convert to lowercase and remove all spaces
  let newName = `${normalizedInputValue}-icon.svg`; // Add the ending to the file name and extension
  return newName;
}

//Function to handle upload button:
function handleFileUpload(element_id) {
    console.log("The parameter from the page:", element_id);
  checkFileType(); // call the function to check whether the admin upload svg-type file or not
  let inputElement = document.getElementById(element_id); // define the input field
  let inputElementValue = inputElement.value; // define the value of input field
  let svgInfoTextElement = document.getElementById("upload-svg-info-text"); //define the element p
  if (inputElementValue === "") {
    svgInfoTextElement.classList.add("alert-message");
    svgInfoTextElement.innerHTML = `First you should to enter the name!`; //inform admin about the need to first enter the name
    inputElement.focus(); // focus on the input field if the input field is empty
  } else {
    innerTextToParagragh(element_id); // call the function to handle p element
  }
}

function checkFileType() {
  let fileElement = document.getElementById("svg-file");
  let fileToCheck = "";
  if ("files" in fileElement) {
    fileToCheck = fileElement.files[0]["type"];
  }
  if (fileToCheck !== "image/svg+xml") {
    handleErrorPageChangeTheTypeOfTheFile();
  }
}

function handleErrorPageChangeTheTypeOfTheFile() {
  alert("Choose another type of the file. It should be .svg");
  document.getElementById("svg-file").value = "";
}

//Function to form and insert info-text depending on whether the admin entered a language name or not:
function innerTextToParagragh(element_id) {
  if (document.getElementById("upload-svg-info-text").classList.contains("alert-message")) {
    document.getElementById("upload-svg-info-text").classList.remove("alert-message");
  }
  let infoText = "";
  let svgInfoTextElement = document.getElementById("upload-svg-info-text"); //define the element p
  let inputElement = document.getElementById(element_id); // define the input field
  let inputElementValue = inputElement.value; // define the value of input field
  if (inputElementValue === "") {
    infoText = `First you should to enter the name!`; // form the text
    inputElement.focus(); // focus on the input field if the input field is empty
  } else {
    let newName = createNameForSvgIcon(element_id);
    infoText = `The icon-file will be uploaded to the project under the name: <b>${newName}</b>`; // form the text
  }
  svgInfoTextElement.innerHTML = infoText; // input text in the p element
}
