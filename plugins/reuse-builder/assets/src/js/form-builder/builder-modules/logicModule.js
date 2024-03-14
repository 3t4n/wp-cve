import { createArray, createEffectField, createLogic, searchObjectIndex } from './helperModule';
import clone from 'clone';
const init = {
  allLogicBlock: [],
  logicBlock: [],
  effectField: [],
};

export default function logicReducer(state = init, action) {
  let  {logicBlock, effectField} = state;
  const  { allLogicBlock } = state;
  switch (action.type) {
    case 'CHANGE_LOGIC':
        return {
          ...tempState,
          viewMode: action.logic,
        };
    case 'ADD_SINGLE_LOGIC_BLOCK':
      const tempAllLogicBlockField = [];
      let tempBlock = [];
        state.allLogicBlock.map(function(logicBlock){
          if (logicBlock.id === action.uid) {
            tempBlock = clone(logicBlock.logicBlock);
            if (tempBlock.length === 0) {
              tempBlock.push(createArray(action.keyType));
            } else {
              tempBlock.push(createArray('relation'));
              tempBlock.push(createArray(action.keyType));
            }
            logicBlock.logicBlock = tempBlock;
            tempAllLogicBlockField.push(logicBlock);
          } else {
            tempAllLogicBlockField.push(logicBlock);
          }
        });
        return {
          ...state,
          allLogicBlock: [...tempAllLogicBlockField],
          logicBlock: [...tempBlock],
        }
    case 'UPDATE_BLOCK':
        const tempLogicBlock = [];
        let singleLogicBlock = [];
        state.allLogicBlock.map(function(logicBlock){
          if(logicBlock.id === action.uid) {
            // singleLogicBlock = clone(logicBlock.logicBlock);
            logicBlock.logicBlock.map(function(block, index) {
              if (block.id === action.id) {
                singleLogicBlock.push(action.data);
              } else {
                singleLogicBlock.push(block);
              }
            });
            logicBlock.logicBlock = clone(singleLogicBlock);
            tempLogicBlock.push(logicBlock);
          } else {
            tempLogicBlock.push(logicBlock);
          }
        });
        return {
          ...state,
          logicBlock: [...singleLogicBlock],
          allLogicBlock: [...tempLogicBlock],
          id: action.uid
        }
    case 'ADD_EFFECT':
        const effectFieldNow =  createEffectField();
        let fIndex = null;
        let logicBlocks = {};
        state.allLogicBlock.forEach(function(singleLogicBlock, blockIndex) {
          if (singleLogicBlock.id === action.uid) {
            fIndex = blockIndex;
          }
        });
        return {
          ...state,
          allLogicBlock: [
            ...state.allLogicBlock.slice(0, fIndex),
            {
              ...state.allLogicBlock[fIndex],
              logicBlock: [
                ...state.allLogicBlock[fIndex].logicBlock.slice(0, state.allLogicBlock[fIndex].logicBlock.length !== 0 ? state.allLogicBlock[fIndex].logicBlock.length : 0),
              ],
              effectField: [
                ...state.allLogicBlock[fIndex].effectField.slice(0, state.allLogicBlock[fIndex].effectField.length !== 0 ? state.allLogicBlock[fIndex].effectField.length : 0),
                effectFieldNow,
              ]
            },
            ...state.allLogicBlock.slice(fIndex+1),
          ],
          effectField: [
            ...state.allLogicBlock[fIndex].effectField.slice(0, state.allLogicBlock[fIndex].effectField.length !== 0 ? state.allLogicBlock[fIndex].effectField.length : 0),
            effectFieldNow,
          ]
        };
      break;
    case 'UPDATE_EFFECT_BLOCK':
        const tempSingleLogic = [];
        const tempEffectblock = [];
        const totalLogicBlock = clone(allLogicBlock);
        let iBlock = null;
        state.allLogicBlock.map(function(logicBlock, blockIndex){
          if (logicBlock.id === action.uid) {
            iBlock = blockIndex;
            logicBlock.effectField.map(function(field, index) {
              if (field.id === action.id) {
                totalLogicBlock[blockIndex].effectField.splice(index, 1, action.data);
              }
            });
          }
        });
        return {
          ...state,
          effectField: [...totalLogicBlock[iBlock].effectField],
          allLogicBlock: [...totalLogicBlock],
        }
        break;
    case 'ADD_LOGIC_BLOCK':
      const tempAllLogicBlock = clone(allLogicBlock);
      tempAllLogicBlock.push(createLogic());
      return {
        ...state,
        allLogicBlock: [...tempAllLogicBlock],
      }
      break;
    case 'OPEN_SINGLE_LOGIC_BLOCK':
      let tempSingleLogicBlock = [];
      let tempSingleEffecFIeld = [];
      state.allLogicBlock.map(function(logicBlock, index){
        if (logicBlock.id === action.id) {
          tempSingleLogicBlock = [...logicBlock.logicBlock];
          tempSingleEffecFIeld = [...logicBlock.effectField];
        }
      });
      return {
        ...state,
        logicBlock: [...tempSingleLogicBlock],
        effectField: [...tempSingleEffecFIeld],
        id: action.id,
      }    
      break;
    case 'ADD_FIELD_TO_BLOCK':
      let tempAllLogicBlockFields = [];
      let tempBlocks = [];

        state.allLogicBlock.map(function(logicBlock){
          if (logicBlock.id === action.uid) {
            const tempRepeatBlock = clone(logicBlock.logicBlock);
            tempRepeatBlock.map(function(tempBlock){
              if (tempBlock.id === action.id) {
                tempBlock.value.value.push(createArray('relation'));
                tempBlock.value.value.push(createArray('field'));
                tempBlocks.push(tempBlock);
              } else {
                tempBlocks.push(tempBlock);
              }
            });
            logicBlock.logicBlock = tempBlocks;
            tempAllLogicBlockFields.push(logicBlock);
          } else {
            tempAllLogicBlockFields.push(logicBlock);
          }
        });
      return {
        ...state,
        allLogicBlock: [...tempAllLogicBlockFields],
        logicBlock: [...tempBlocks],
        id: action.uid,
      }
      break;
    case 'DELETE_SINGLE_LOGIC_BLOCK':
      const temporayAllLogicBlock = [];
      state.allLogicBlock.map(function(logicBlock){
        if (logicBlock.id !== action.id) {
          temporayAllLogicBlock.push(logicBlock);
        }
      });
      return {
        ...state,
      allLogicBlock: [...temporayAllLogicBlock],
      logicBlock: [],
      effectField: [],
      id: action.uid,
      }
    case 'DELETE_SINGLE_LOGIC_BLOCK_FIELD':
      let result = clone(allLogicBlock);
      let targetIndex = 0;
      state.allLogicBlock.map(function(logicBlock, blockIndex) {
        if (logicBlock.id === action.uid ) {
          targetIndex = blockIndex;
          const index = searchObjectIndex(logicBlock.logicBlock, 'id', action.id);
          // console.log(index);
          const fieldblock = logicBlock.logicBlock;
          if (fieldblock.length === 1) {
            result[blockIndex].logicBlock = [];
          } else if (index === 0) {
            result[blockIndex].logicBlock.splice(index, 2);
          } else {
            result[blockIndex].logicBlock.splice(index-1, 2);
          }
        }
      });
      return {
        ...state,
        allLogicBlock: [...result],
        logicBlock: [...result[targetIndex].logicBlock],
      }
    case 'DELETE_BLOCK_FIELD':
      let response = [...allLogicBlock];
      let responseIndex = 0;
      state.allLogicBlock.map(function(logicBlock, blockIndex) {
        if (logicBlock.id === action.uid ) {
          responseIndex = blockIndex;
          const indexOfBlock = searchObjectIndex(logicBlock.logicBlock, 'id', action.blockId);
          const block = logicBlock.logicBlock[indexOfBlock].value.value;
          const indexOfField = searchObjectIndex(block, 'id', action.id);
          if (block.length === 1) {
            response[blockIndex].logicBlock[indexOfBlock].value.value = [];
          } else if (indexOfField === 0) {
            response[blockIndex].logicBlock[indexOfBlock].value.value.splice(indexOfField, 2);
          } else {
            response[blockIndex].logicBlock[indexOfBlock].value.value.splice(indexOfField-1, 2);
          }
        }
      });
      return {
        ...state,
        allLogicBlock: [...response],
        logicBlock: [...response[responseIndex].logicBlock],
      }
    case 'DELETE_CONSEQUENCE_FIELD':
      let res = clone(allLogicBlock);
      let resIndex = 0;
      state.allLogicBlock.map(function(logicBlock, blockIndex) {
        if (logicBlock.id === action.uid ) {
          resIndex = blockIndex;
          const indexOfBlock = searchObjectIndex(logicBlock.effectField, 'id', action.id);
          if (indexOfBlock !== -1) {
            res[blockIndex].effectField.splice(indexOfBlock, 1);
          }
        }
      });
      return {
        ...state,
        allLogicBlock: [...res],
        effectField: [...res[resIndex].effectField],
      }
    case 'LOAD_INITIAL_DATA':
      return {
        ...state,
        allLogicBlock: [
          ...state.allLogicBlock,
          ...action.data
        ],
      }
    default: {
      return state;
    }
  }
}


export function changeLogic(logic) {
  return {
    type: 'CHANGE_LOGIC',
    logic,
  };
}

export function addSingleLogicBlock(keyType, uid) {
  return {
    type: 'ADD_SINGLE_LOGIC_BLOCK',
    keyType,
    uid,
  };
}

export function updateBlockData(id, data, blockId) {
  return {
    type: 'UPDATE_BLOCK',
    id,
    data,
    uid: blockId,
  };
}

export function addEffectField(uid) {
  return {
    type: 'ADD_EFFECT',
    uid,
  };
}

export function updateEffectField(id, data, blockId) {
  return {
    type: 'UPDATE_EFFECT_BLOCK',
    id,
    data,
    uid: blockId,
  };
}

export function addLogicBlock() {
  return {
    type: 'ADD_LOGIC_BLOCK',
  };
}

export function selectLogicBlock(id) {
  return {
    type: 'OPEN_SINGLE_LOGIC_BLOCK',
    id,
  }
}

export function addFieldTOBlock(id, uid) {
  return {
    type: 'ADD_FIELD_TO_BLOCK',
    id,
    uid,
  }
}

export function deleteLogicBlock(id) {
  return {
    type: 'DELETE_SINGLE_LOGIC_BLOCK',
    id,
  }
}

export function deleteSingleLogicBlockField(id, uid) {
  return {
    type: 'DELETE_SINGLE_LOGIC_BLOCK_FIELD',
    id,
    uid,
  }
}

export function deleteBlockField(id, blockId, uid) {
  return {
    type: 'DELETE_BLOCK_FIELD',
    id,
    blockId,
    uid,
  }
}

export function deleteConsequence(id, uid) {
  return {
    type: 'DELETE_CONSEQUENCE_FIELD',
    id,
    uid,
  }
}

export function setLogicBlock(data) {
  return {
    type: 'LOAD_INITIAL_DATA',
    data,
  }
}
