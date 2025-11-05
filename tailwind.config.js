/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./**/*.php",
    "./template-parts/**/*.php",
    "./inc/**/*.php",
    "./js/**/*.js",

    "../generatepress-child/*.php",
    "../generatepress-child/**/*.php",
    "../generatepress-child/assets/js/**/*.js",
    "../generatepress-child/template-parts/**/*.php",
    "../generatepress-child/inc/**/*.php",
    "../generatepress-child/js/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
