import  rn  from 'random-number';
import clone from 'clone';
export function createArray(keyType) {
    let obj = {};
    const options = {
      min:  1,
      max:  1000, 
      integer: true
    };
    const rnumber = rn(options);
    switch(keyType) {
      case 'field':
        obj = {
          id: new Date().getTime() + rnumber,
          key: keyType,
          value: {
              fieldID: null,
              secondOperand: {
                type: 'value',
                value: undefined,
              },
              operator: null,
          },
          childresult: null,
        }
        break;
      case 'relation':
        obj = {
          id: new Date().getTime() + rnumber,
          key: keyType,
          value: null,
        }
        break;
      default:
        const date = new Date().getTime() + rnumber;
        obj =  {
          id: date,
          key: 'innerExpression',
          value: {
            value: [
              {
                id: date + '-first',
                key: 'field',
                value: {
                  fieldID: null,
                  secondOperand: {
                    type: 'value',
                    value: undefined,
                  },
                  operator: null,
                },
                childresult: null,
              },
              {
                id: date + '-second',
                key: 'relation',
                value: null,
              },
              {
                id: date + '-third',
                key: 'field',
                value:
                {
                  fieldID: null,
                  secondOperand: {
                    type: 'value',
                    value: undefined,
                  },
                  operator: null,
                },
                childresult: null,
              }
            ],
          },
          childresult: null,
        };
        break;
    }
    return {
      ...obj,
    };
  }

export function createEffectField() {
    const options = {
      min:  1,
      max:  1000, 
      integer: true
    };
    const rnumber = rn(options);
    let obj = {};
    obj.action = null;
    obj.id = new Date().getTime() + rnumber;
    return {...obj};
}

export function createLogic() {
    const options = {
      min:  1,
      max:  1000, 
      integer: true
    };
    const rnumber = rn(options);
    let obj = {};
    obj.name = 'condition' + rnumber;
    obj.id = new Date().getTime() + rnumber;
    obj.logicBlock = clone([]);
    obj.effectField = clone([]);
    return {...obj};
}

export function searchObjectIndex(items=[], objId="", objValue="") {
  let index = -1;
  items.map(function(item, itemIndex){
    if (item[objId] === objValue) {
      index = itemIndex;
    }
  });
  return index;
}
