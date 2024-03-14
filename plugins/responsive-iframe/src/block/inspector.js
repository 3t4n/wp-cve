const { InspectorControls,InspectorAdvancedControls} = wp.blockEditor;
const { Component } = wp.element;
const { Panel,PanelBody, TextControl, CheckboxControl,RangeControl,Button} = wp.components;

//import structures
import Structures from '../js/structures';

const DEFAULTS = {
    BREAKPOINTS:{
        BREAKPOINT_TITLE:"BreakPoint",
        BREAKPOINT_W:1000,
        IFRAME_W:1000
    }
}

export default class Inspector extends Component{
    constructor(props){
        super(props);        
    }

    addBreakPoint(){
        let {attributes,setAttributes} = this.props;
        let hardCopyBreakpoints = JSON.parse(JSON.stringify(attributes.breakPoints));
        
        const breakpoint = new Structures.BreakPoint(DEFAULTS.BREAKPOINTS.BREAKPOINT_TITLE,
            DEFAULTS.BREAKPOINTS.BREAKPOINT_W,DEFAULTS.BREAKPOINTS.IFRAME_W);
        hardCopyBreakpoints.push(breakpoint);
        
        setAttributes({
            breakPoints:hardCopyBreakpoints
        });
    }

    updateBreakPoint(newBreakP_W, newSiteW, index){
        let {attributes,setAttributes} = this.props;
        let breakPoints = JSON.parse(JSON.stringify(attributes.breakPoints));

        breakPoints[index].breakPointW = newBreakP_W;
        breakPoints[index].siteW = newSiteW;

        setAttributes({
            breakPoints:breakPoints
        });        
    }

    updateBreakPointTitle(newTitle,index){
        let {attributes,setAttributes} = this.props;
        let breakPoints = JSON.parse(JSON.stringify(attributes.breakPoints));

        breakPoints[index].breakPointTitle = newTitle;

        setAttributes({
            breakPoints:breakPoints
        });   
    }

    removeBreakPoint(index) {
        let {attributes,setAttributes} = this.props;
        let breakPoints = JSON.parse(JSON.stringify(attributes.breakPoints));

        breakPoints.splice(index,1);
        setAttributes({
            breakPoints:breakPoints
        });      
    }
    removeAllBreakPoints(){
        let {attributes,setAttributes} = this.props;
        
        let breakPoints = attributes.breakPoints;

        breakPoints = [];
        setAttributes({
            breakPoints:breakPoints
        });
    }

    render(){
        let {attributes,setAttributes} = this.props; 
        let breakPoints = attributes.breakPoints;
        
        return(
            <InspectorControls>
                <Panel>
                    <PanelBody title='Iframe Settings'>
                        <TextControl 
                            label ='Site Address'
                            value = {attributes.iFrameURL}
                            onChange= {(val)=>{setAttributes({iFrameURL:val});}}
                        />
                        <RangeControl 
                            label ='Site Width'
                            min={0}
                            max={3000}
                            value = {attributes.iFrameWidth}
                            onChange= {(val)=>{setAttributes({iFrameWidth:val});}}
                        />
                        <RangeControl 
                            label ='Site Height'
                            min={0}
                            max={3000}
                            value = {attributes.iFrameHeight}
                            onChange= {(val)=>{setAttributes({iFrameHeight:val});}}
                        />
                        <div className={RESPONSIVE_IFRAME_CONSTANTS.CHK_BX_CLASS}>
                            <CheckboxControl
                                label = 'Scroll Bar'
                                checked = {attributes.scrollBarChecked}
                                onChange = {()=>{setAttributes({scrollBarChecked:!attributes.scrollBarChecked});}}
                            />
                            <CheckboxControl
                                label = 'Border'
                                checked = {attributes.borderChecked}
                                onChange = {()=>{setAttributes({borderChecked:!attributes.borderChecked});}}
                            />
                        </div>

                        <RangeControl
                            label={!attributes.useMaxWidth ? "Scale" : "Scale (Disabled by Advanced Setting MAX WIDTH)"}
                            disabled={attributes.useMaxWidth}
                            min={1}
                            max={100}
                            value = {attributes.wrapperWidth}
                            onChange= {(val)=>{setAttributes({wrapperWidth:val});}}
                        />  
                    </PanelBody>
                    <InspectorAdvancedControls>
                        <PanelBody title="BreakPoints" initialOpen={false}>
                            <Button className="responsiveIframeButton" onClick={()=>{this.addBreakPoint();}}>Add Break Point</Button>
                            <ul>
                                {breakPoints.map((bp,index)=>{
                                    return <PanelBody title={bp.breakPointTitle !== "" ? bp.breakPointTitle : DEFAULTS.BREAKPOINTS.BREAKPOINT_TITLE} initialOpen={false}>
                                            <li>
                                                <TextControl label="Name" value = {bp.breakPointTitle} 
                            onChange= {(val)=>{this.updateBreakPointTitle(val,index)}}></TextControl>
                                                <RangeControl label="BreakPoint Width" min={0} max={3000} value={bp.breakPointW} onChange={(val)=>{this.updateBreakPoint(val,bp.siteW,index)}}></RangeControl>
                                                <RangeControl label="Site Width" min={0} max={3000} value={bp.siteW} onChange={(val)=>{this.updateBreakPoint(bp.breakPointW,val,index)}}></RangeControl>
                                                <Button className="responsiveIframeButtonRed" onClick={()=>{this.removeBreakPoint(index)}}>Remove BreakPoint</Button>
                                            </li>
                                        </PanelBody>
                                })}
                            </ul>
                            <Button className="responsiveIframeButtonRed" onClick={()=>{this.removeAllBreakPoints();}}>Remove All BreakPoints</Button>
                        </PanelBody>
                        

                        <PanelBody title="Other Options" initialOpen={false}>
                            <CheckboxControl
                                        label = 'UseMaxWidth(This will disable scaling)'
                                        checked = {attributes.useMaxWidth}
                                        onChange = {()=>{setAttributes({useMaxWidth:!attributes.useMaxWidth});}}
                                    />
                            <RangeControl
                                disabled={!attributes.useMaxWidth}
                                label="Max-Width"
                                min={1}
                                max={3000}
                                value = {attributes.maxWidth}
                                onChange= {(val)=>{setAttributes({maxWidth:val});}}
                            />  
                        </PanelBody>
				    </InspectorAdvancedControls>
                </Panel>
            </InspectorControls>
        )
    }
            
}