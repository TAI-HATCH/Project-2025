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
  if (
    document
      .getElementById("upload-svg-info-text")
      .classList.contains("alert-message")
  ) {
    document
      .getElementById("upload-svg-info-text")
      .classList.remove("alert-message");
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

function activateButton(element_name) {
  const inputName = document.getElementById(element_name);
  const inputFile = document.getElementById("svg-file");
  const buttonToActivate = document.getElementById("submitBtn");

  if (inputName.value.trim() !== "" || inputFile.value.trim() !== "") {
    buttonToActivate.disabled = false;
  } else {
    buttonToActivate.disabled = true;
  }
}

function validateForm(event, element_name) {
  const inputName = document.getElementById(element_name);
  const inputFile = document.getElementById("svg-file");
  const buttonToActivate = document.getElementById("submitBtn");

  if (inputName.value.trim() === "") {
    buttonToActivate.disabled = true;
    alert("Enter the name");
    event.preventDefault();
  } else if (inputFile.value.trim() === "") {
    buttonToActivate.disabled = true;
    alert("Upload the file");
    event.preventDefault();
  }
}

function handleRadioButtonText() {
  const tagForText = document.getElementById("image-inform-text");
  const radioBtns = document.querySelectorAll("input[name='image']");
  let text = "";
  let valueForHiddeninput = "";
  let hiddenInputElementForFileAction = document.querySelector("input[name='server-file-action']");

  radioBtns.forEach((btn) => {
    btn.addEventListener("change", (event) => {
      if (event.target.checked) {
        if (event.target.value === "new upload") {
          // console.log("new upload");
          text =
            "<strong>Please note</strong>: The existing file will be deleted. The file you uploaded will be saved in its place.";
          tagForText.innerHTML = text;
          valueForHiddeninput = "upload-new";
          hiddenInputElementForFileAction.value = valueForHiddeninput;

        } else if (event.target.value === "existing image") {
          // console.log('existing');
          text =
            "<strong>Please note</strong>: The file you uploaded will be deleted. The existing file will remain.";
          tagForText.innerHTML = text;
          valueForHiddeninput = "keep-existing";
          hiddenInputElementForFileAction.value = valueForHiddeninput;
        }
      }
    });
  });
}

function handleCheckboxUncheck(element) {
  const checkBoxGroup = element.closest(".checkbox-group");
  const checkBoxChild = checkBoxGroup.querySelectorAll(".checkbox-child");
  if (!element.checked) {
    checkBoxChild.forEach((child) => {
      console.log(child);
      child.checked = false;
    });
  }
}

window.addEventListener("DOMContentLoaded", () => {
  document
    .querySelectorAll(".question-list-item-snippet")
    .forEach((element) => {
      handlePlaceholder(element);
    });
});

function handlePlaceholder(element) {
  const inputElements = element.querySelectorAll("input");
  inputElements.forEach((element) => {
    element.placeholder = element.name;
  });
}

window.addEventListener("DOMContentLoaded", () => {
  const divHiddenInputTopic = document.getElementById("form-input-hidden-topics");
  if (!divHiddenInputTopic) return;
  let allCheckBoxTopic = document.querySelectorAll("input[name='topic[]']");
  if (allCheckBoxTopic.length > 0) {
    allCheckBoxTopic.forEach((item) => {
      item.addEventListener("click", () => {
        refreshFormDivTopics(divHiddenInputTopic);
      });
    });
    let checkedTopics = document.querySelectorAll("input[name='topic[]']:checked");
    if (checkedTopics.length > 0) {
      addHiddenInputElements(checkedTopics, "topic[]", divHiddenInputTopic);
    }
    
  }
  
  const divHiddenInputQuestion = document.getElementById("form-input-hidden-questions");
  let allCheckBoxQuestion = document.querySelectorAll("input[name='question[]']");
  if (allCheckBoxQuestion.length > 0) {
    allCheckBoxQuestion.forEach((item) => {
      item.addEventListener("change", () => {
        refreshFormDivQuestions(divHiddenInputQuestion);
      });
    });
    let checkedQuestions = document.querySelectorAll("input[name='question[]']:checked");
    if (checkedQuestions.length > 0) {
      addHiddenInputElements(checkedQuestions, "question[]", divHiddenInputQuestion);
    }
   
  }
  
  const divHiddenInputAnswer = document.getElementById("form-input-hidden-answers");
  let allCheckBoxAnswer = document.querySelectorAll("input[name='answer[]']");
  if (allCheckBoxAnswer.length > 0) {
    allCheckBoxAnswer.forEach((item) => {
      item.addEventListener("change", () => {
        refreshFormDivAnswers(divHiddenInputAnswer);
      });
    });
    let checkedAnswers = document.querySelectorAll("input[name='answer[]']:checked");
    if (checkedAnswers.length > 0) {
      addHiddenInputElements(checkedAnswers, "answer[]", divHiddenInputAnswer);
    }
  }
});


function refreshFormDivTopics(element) {
  let checkedTopics = document.querySelectorAll("input[name='topic[]']:checked");
  addHiddenInputElements(checkedTopics, "topic[]", element);
}

function refreshFormDivQuestions(element) {
  let checkedQuestions = document.querySelectorAll("input[name='question[]']:checked");
  addHiddenInputElements(checkedQuestions, "question[]", element);
  addHiddenInputElements(document.querySelectorAll("input[name='answer[]']:checked"), "answer[]", document.getElementById("form-input-hidden-answers"));
}

function refreshFormDivAnswers(element) {
  // console.log("Element to change:", element);
  let checkedAnswers = document.querySelectorAll("input[name='answer[]']:checked");
  addHiddenInputElements(checkedAnswers, "answer[]", element);
}

function addHiddenInputElements(arrayOfElements, inputName, targetDiv) {
  targetDiv.innerHTML = "";
  arrayOfElements.forEach((item) => {
    const hiddenInputItem = document.createElement("input");
    hiddenInputItem.name = inputName;
    hiddenInputItem.type = "hidden";
    hiddenInputItem.value = item.defaultValue;
    targetDiv.appendChild(hiddenInputItem);
  });
}
