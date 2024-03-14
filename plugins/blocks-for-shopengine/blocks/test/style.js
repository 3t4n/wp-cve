
const Style = ({settings, breakpoints, cssHelper})=>{
    // cssHelper.add('.easin', 'font-size', '24px')
    // cssHelper.add('.easin', 'color', settings.simple_test.desktop)

    cssHelper.add('h1', settings.shopengine_product_title_font_family, (val) => (`
            font-family: '${val.family}';
        `));
    cssHelper.add('.easin, .selector2', settings.simple_test, (val) => (`
            font-size: 24px;
            color: ${val};
        `));

    cssHelper.add('.selector3', settings.simple_test2, (val) => (`
            background-color: ${val};
        `))


    
    // cssHelper.add(`
    //     #${settings.blockId} .class-a{
    //         color: #ab0;
    //     }
    //     #${settings.blockId} .class-b{
    //         color: ${settings.color};
    //     }
    // `)

    return cssHelper.get()
}

/*
const Style = ({settings, breakpoints})=>{
    // cssHelper.media(`(min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px)`)
    // .add('.class-a', 'color', '#000')
    // .add('.class-b', 'color', settings.autocontrol1)

    var cssOutput = '';

    var cssOutput = `
        #${settings.blockId} .class-a{
            color: #ab0;
        }
        #${settings.blockId} .class-b{
            color: ${settings.color};
        }
    `

    // we can apply conditional styles and concat them
    cssOutput += `

    @media (min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px){
        #${settings.blockId} .class-a{
            color: #000;
        }
        #${settings.blockId} .class-b{
            color: ${settings.autocontrol1};
        }
    }
    `

    return cssOutput
}
*/

export {Style}