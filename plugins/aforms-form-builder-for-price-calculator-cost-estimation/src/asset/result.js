
import { h, app } from 'hyperapp';
import number_format from 'locutus/php/strings/number_format';
import { tnOnCreate, tnOnRemove, Image, TextInput, TextArea, RadioButton, Checkbox, Select, Range, Echo, Button, InputGroup, Control, FileInput, File } from './front_component';


/*
 * Polyfills
 */
Math.trunc = Math.trunc || function(x) {
  return x < 0 ? Math.ceil(x) : Math.floor(x);
}

/*
 * ===============================================================
 * utilities
 */

function sprintf(format) {
  var args = arguments;
  var offset = 1;
  return format.replace(/%([0-9]\$)?([^0-9])/g, function (match, f1, f2) {
    if (f2 == '%') {
      return '%';
    } else if (f2 == 's') {
      if (! f1) {
        return args[offset++];
      } else {
        return args[f1.slice(0, 1)];
      }
    }
  });
}

const nf = (amount) => {
  return number_format(amount, order.currency.taxPrecision, order.currency.decPoint, order.currency.thousandsSep);
}

const br = {nodeName:'br', attributes:{}, children:[]}

const _Tv = (x) => {
  x = _T(x)
  if (typeof x != 'string') return x

  const lines = x.split('\n')
  if (lines.length == 1) return lines[0]
  
  return lines.reduce((cur, line) => {
    return (cur.length == 0) ? [line] : [...cur, br, line]
  }, [])
}

const _T = (x) => {
  if (typeof x == 'undefined' || x === null) return null
  if (catalog.hasOwnProperty(x)) {
    return catalog[x]
  } else {
    console.log('TO TRANSLATE: ', x);
    return x;
  }
}

const reduceHash = (f, cur, hash) => {
  for (let key in hash) {
    cur = f(cur, key, hash[key])
  }
  return cur
}


const Monitor = () => {
  return (
    <div class="wq-Monitor wq-is-result" id="wq-monitor">
      <div class="wq--header">
        <div class="wq--title">{_T('Quotation Details')}</div>
      </div>
      <div class="wq--entries">
        <div class="wq--entry wq-for-header">
          <div class="wq--prop wq-for-no">{_Tv('No')}</div>
          <div class="wq--prop wq-for-category">{_Tv('Category')}</div>
          <div class="wq--prop wq-for-entry">{_Tv('Entry')}</div>
          <div class="wq--prop wq-for-normalUnitPrice">{_T('Regular Unit Price')}</div>
          <div class="wq--prop wq-for-unitPrice">{_Tv('Unit Price')}</div>
          <div class="wq--prop wq-for-quantity">{_Tv('Quantity')}</div>
          <div class="wq--prop wq-for-price">{_Tv('Price')}</div>
          {order.hasOwnProperty('taxes') && (<div class="wq--prop wq-for-taxClass">{_Tv('Tax Class')}</div>)}
        </div>
        {order.details.map((detail, i) => {
          return (
            <div class="wq--entry wq-for-entry" id={i}>
              <div class="wq--prop wq-for-no">{i + 1}</div>
              <div class="wq--prop wq-for-category">{detail.category}</div>
              <div class="wq--prop wq-for-entry">{detail.name}</div>
              <div class="wq--prop wq-for-unitPrice">{detail.unitPrice !== null ? nf(detail.unitPrice) : null}</div>
              <div class="wq--prop wq-for-quantity"><span class="wq--simpleDisplay">{detail.unitPrice !== null ? detail.quantity : null}</span><span class="wq--independentDisplay">{detail.unitPrice !== null ? sprintf(_T('x%s'), detail.quantity) : null}</span></div>
              <div class="wq--prop wq-for-price">{detail.unitPrice !== null ? nf(detail.price) : null}</div>
              {order.hasOwnProperty('taxes') && (<div class="wq--prop wq-for-taxClass">{detail.unitPrice !== null ? (detail.taxRate === null ? sprintf(_Tv('(common %s%% applied)'), order.defaultTaxRate) : sprintf(_Tv('(%s%% applied)'), detail.taxRate)) : null}</div>)}
            </div>
          )
        })}
      </div>
      {!order.hasOwnProperty('subtotal') ? (
        <div class="wq--footer">
          <div class="wq--entry wq-for-total">
            <div class="wq--prop wq-for-name">{_Tv('Total')}</div>
            <div class="wq--prop wq-for-value">{order.currency.pricePrefix}<span><span>{nf(order.total)}</span></span>{order.currency.priceSuffix}</div>
          </div>
        </div>
      ) : (
        <div class="wq--footer">
          <div class="wq--entry wq-for-subtotal">
            <div class="wq--prop wq-for-name">{_Tv('Subtotal')}</div>
            <div class="wq--prop wq-for-value">{order.currency.pricePrefix}<span><span>{nf(order.subtotal)}</span></span>{order.currency.priceSuffix}</div>
          </div>
          {order.hasOwnProperty('taxes') && order.taxes[''] > 0 ? (
            <div class="wq--entry wq-for-tax wq-rate-common">
              <div class="wq--prop wq-for-name">{sprintf(_Tv('Tax (common %s%%)'), ""+order.defaultTaxRate)}</div>
              <div class="wq--prop wq-for-value">{order.currency.pricePrefix}<span><span>{nf(order.taxes[''])}</span></span>{order.currency.priceSuffix}</div>
            </div>
          ) : null}
          {reduceHash((cur, key, tax) => {
            if (key === "") return cur
            return [...cur, (
              <div class="wq--entry wq-for-tax wq-rate-individual">
                <div class="wq--prop wq-for-name">{sprintf(_Tv('Tax (%s%%)'), ""+key)}</div>
                <div class="wq--prop wq-for-value">{order.currency.pricePrefix}<span><span>{nf(tax)}</span></span>{order.currency.priceSuffix}</div>
              </div>
            )]
          }, [], order.taxes)}
          <div class="wq--entry wq-for-total">
            <div class="wq--prop wq-for-name">{_Tv('Total')}</div>
            <div class="wq--prop wq-for-value">{order.currency.pricePrefix}<span><span>{nf(order.total)}</span></span>{order.currency.priceSuffix}</div>
          </div>
        </div>
      )}
    </div>
  )
}


const catalog = wqData.catalog;
const order = wqData.order

const showLines = (value) => {
  const text = (value === null) ? '' : value
  return text.split(/\r?\n/).reduce((result, line) => {
    result.push(line)
    result.push(<br></br>)
    return result
  }, [])
}
const showAttrValue = (value) => {
  if (Array.isArray(value)) {
    if (value.length > 0 && value[0] !== null && typeof value[0] == 'object') {
      // external list
      // TODO: check security
      return (
        <InputGroup gutter="mini" xclass="wq-belongs-attr wq-belongs-file">
          {value.map((file, i) => {
            return (
              <File index={i} url={file.url} name={file.name} readonly />
            )
          })}
        </InputGroup>
      )
    } else {
      return (
        <Echo value={value} xclass="wq-belongs-attr" glue={_T(', ')} />
      )
    }
  } else {
    if (value !== null && typeof value == 'object') {
      // external
      // TODO: check security
      return (
        <File index={0} url={value.url} name={value.name} readonly />
      )
    } else {
      return (
        <Echo value={value} xclass="wq-belongs-attr" glue={_T(', ')} />
      )
    }
  }
}

const view = () => {
  return (
    <div class="wq-Form wq-is-result">
      <Monitor />
      <div class="wq-Attributes wq-is-result">
        {order.attrs.map((attr, i) => {
          return (
            <Control label={attr.name} id={i}>
              {showAttrValue(attr.value)}
            </Control>
          )
        })}
      </div>
    </div>
  )
}

app({}, {}, view, document.getElementById('root'))