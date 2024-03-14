const fs = require('fs')
const path = require('path')
const sass = require('sass');

// const result = sass.compile("application.scss");

const compressed = sass.compile("application.scss", { style: "compressed" });

const writeOutput = (output, dir = './js_output.txt') => {
  fs.writeFile(path.join(__dirname, dir), output, function (err) {
    if (err) return console.log(err)
    console.log("finished")
  })
};


writeOutput(compressed.css, "./landing-page.css");
