import {Template} from "../../types/types";
import TemplateCard from "./TemplateCard";
import {ActionTypes, DisplayModes} from "../../types/aux-types";

interface TemplateCardsPros {
    templates: Template[]
    setTemplate: Function
    types: ActionTypes
    displayModes: DisplayModes
}
/**
 * Pass in the templates
 *
 * Render a Template Card each
 * @constructor
 */
const TemplateCards = ({templates, setTemplate, types, displayModes}: TemplateCardsPros) => {
    return <section className="cnb-grid cnb-grid-4columns">{templates.map((template) => {
        return <TemplateCard template={template} types={types} displayModes={displayModes} handleSelect={(template) => setTemplate(template)} />
    })}</section>
}

export default TemplateCards
