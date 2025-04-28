const questions = [
  {
    question_id: 1,
    question: `Write a correct syntax for assigning a value to a variable:<br>myNumber <input name="input"> 9`,
  },
  {
    question_id: 1,
    question: `Write a correct syntax for assigning a value to a variable of type number and value 9:<br>myNumber <input correct="=" incorrect="???"> <input value="9">`,
    to_be_stored_in_session: {
        question: `Write a correct syntax for assigning a value to a variable of type number and value 9:<br>myNumber <input> <input>`,
        answers: ["=", "9"]
    },
  },
  {
    question_id: 2,
    question: "How would you assign a value to a variable, if it\'s supposed to be the word <i>number</i>?\n",
    snippet: ["myString ="],
    answer: ["\'number\'", "\"number\""],
  },
  {
    question_id: 3,
    question: "If you want to count the number of characters in a text, you would use the built-in property:\n",
    snippet: ["let myText = \"Ihana!\"/nlet amountOfCharacters = myText."],
    answer: ["length"],
  },
  {
    question_id: 4,
    question: "If you don\'t want to change the value of the variable price in your code, what keyword would you use to declare it?\n",
    snippet: ["price = 100"],
    answer: ["const"],
  },
  {
    question_id: 5,
    question: "If you want to increase your counter <i>index</i> by 1, you can write:\n",
    snippet: ["let i = 1\ni = i + 1\nor\ni", "1"],
    answer: ["+="],
  },
//   {
//     question_id: 6,
//     question: "\n",
//     snippet: [""],
//     answer: [""],
//   },
];
