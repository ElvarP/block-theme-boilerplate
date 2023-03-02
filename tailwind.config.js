/**
 * See https://tailwindcss.com/docs/configuration for configuration details
 */

/**
 * Convert pixels to rems
 * @param {int} px The pixel value to convert to rems
 */

const fs = require("fs")
const { glob, globSync } = require("glob")

const themeJson = fs.readFileSync("./theme.json")
const theme = JSON.parse(themeJson)

const rem = (px) => `${px / 16}rem`

let colors = {}
theme.settings.color.palette.forEach((color) => {
	colors[color.slug] = `var(--wp--preset--color--${color.slug})`
})

let fonts = {}
theme.settings.typography.fontFamilies.forEach((fam) => {
	fonts[fam.slug] = fam.fontFamily.split(",")
})
// let fontSizes = {}
// theme.settings.typography.fontSizes.forEach((fontSize) => {
// 	fontSizes[fontSize.slug] = `var(--wp--preset--font-size--${fontSize.slug})`
// })

module.exports = {
	content: ["./inc/**/*.php", "./template-parts/**/*.php", "./blocks/**/*.php"],
	// have to use glob sync because otherwise base folder becomes tw dependency and infinite loop because of index.asset.php
	// glob returns array of actual files and this way build folder is definitively ignored
	theme: {
		fontFamily: fonts,
		extend: {
			colors: colors,
			// fontSize: fontSizes,
		},
	},
	corePlugins: {
		fontSize: false,
	},
	plugins: [require("tailwindcss-fluid-type")],
}
