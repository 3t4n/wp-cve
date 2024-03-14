import {
  Fragment,
  React,
  useContext,
  useEffect,
  useRef,
  useState,
} from 'react';
import AddOrEditEventContext from '../context/AddOrEditEventContext';
import DeleteItemModal from './DeleteItemModal';
import ItemForm from './ItemForm';

function classNames(...classes) {
  return classes.filter(Boolean).join(' ');
}

export default function ItemsTable({ eventName, eventItems, updateFunction }) {
  const checkbox = useRef();
  const [items, setItems] = useState(
    Array.isArray(eventItems) ? eventItems : [],
  );
  //const [item, setItem] = useState()
  const [checked, setChecked] = useState(false);
  const [indeterminate, setIndeterminate] = useState(false);
  const [selectedItems, setSelectedItems] = useState([]);
  const { openItemsForm, setOpenItemsForm } = useContext(AddOrEditEventContext);
  const [addItemForm, setAddItemForm] = useState();
  const [delModal, setDelModal] = useState(false);
  const [currentItem, setCurrentItem] = useState({});
  const [currentItemIndex, setCurrentItemIndex] = useState();
  const [editCurrentItem, setEditCurrentItem] = useState(false);

  //useLayoutEffect(() => {
  //  const isIndeterminate = selectedItems.length > 0 && selectedItems.length < items.length
  // setChecked(selectedItems.length === Items.length)
  // setIndeterminate(isIndeterminate)
  // checkbox.current.indeterminate = isIndeterminate
  //}, [selectedItems])

  useEffect(() => {
    updateFunction(items);
  }, [items]);

  function openAddItemForm() {
    setCurrentItem({});
    setCurrentItemIndex(-1);
    setOpenItemsForm(true);
  }

  function toggleAddItemForm() {
    setOpenItemsForm(!addItemForm);
  }

  function toggleAll() {
    setSelectedItems(checked || indeterminate ? [] : items);
    setChecked(!checked && !indeterminate);
    setIndeterminate(false);
  }

  function toggleDeleteModal() {
    setDelModal(!delModal);
  }

  function toggleEditForm(e, item, index) {
    e.preventDefault();
    let current = { ...item };
    setCurrentItem(current);
    setCurrentItemIndex(index);
    setOpenItemsForm(!openItemsForm);
  }

  function hideEditForm() {
    setCurrentItem({});
  }

  function showDeleteModal(e, item, index) {
    e.preventDefault();
    let current = { ...item };
    current['index'] = index;
    setCurrentItem(current);
    setDelModal(true);
  }

  const addItem = newItem => {
    //setItem(newItem)
    setItems([...items, newItem]);
  };

  const saveItem = (item, index) => {
    if (index >= 0) {
      let allItems = items;
      allItems[index] = item;
      setItems([...allItems]);
    } else {
      setItems([...items, item]);
    }
  };

  function setItemProp() {
    setCurrentItem(false);
  }

  const deleteItem = index => {
    let allItems = items;
    allItems.splice(index, 1);
    setItems(allItems);
    setCurrentItem(false);
  };

  return (
    <div
      data-component="items-table"
      className="flex flex-col bg-gray-50 rounded-50"
    >
      <header className="p-4 sm:flex sm:items-center">
        <div className="sm:flex-auto">
          <h3 className="uppercase text-base leading-6 font-medium text-gray-900">
            Items
          </h3>
        </div>
        <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            onClick={openAddItemForm}
            type="button"
            className="capitalize inline-flex items-center justify-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2 sm:w-auto"
          >
            Add Item
          </button>
        </div>
      </header>
      <div
        data-component="item-list"
        className="bg-gray-100 p-2 flex items-center"
      >
        {items && items.length > 0 ? (
          <table className="min-w-full table-fixed divide-y divide-gray-300">
            <thead className="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  className="min-w-[12rem] py-3.5 pr-2 pl-4 text-left text-sm font-semibold text-gray-900"
                >
                  Item ID
                </th>
                <th
                  scope="col"
                  className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                >
                  Item Name
                </th>
                <th
                  scope="col"
                  className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                ></th>
                <th scope="col" className="relative py-3.5 pl-3 pr-4 sm:pr-6">
                  <span className="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody className=" bg-white">
              {items && items.length > 0 && items[0]
                ? items.map((item, index) => (
                    <Fragment key={index}>
                      {
                        <tr className="bg-gray-50">
                          <td
                            className={classNames(
                              'whitespace-nowrap pr-2 pl-4 text-sm font-medium',
                              'py-1',
                              'pt-2',
                              selectedItems.includes(item)
                                ? 'text-gtIndigo-600'
                                : 'text-gray-900',
                            )}
                          >
                            {item.item_id}
                          </td>
                          <td
                            rowSpan="2"
                            className="whitespace-nowrap px-3 text-sm text-gray-500"
                          >
                            {item.item_name}
                          </td>
                          <td
                            rowSpan="2"
                            className="whitespace-nowrap px-3 text-sm text-gray-500"
                          ></td>
                          <td
                            rowSpan="2"
                            className="whitespace-nowrap  py-6 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                          >
                            <button
                              type="button"
                              onClick={e => toggleEditForm(e, item, index)}
                              className="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-brand-primary hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary-focus"
                            >
                              Edit
                            </button>
                            <button
                              type="button"
                              onClick={e => showDeleteModal(e, item, index)}
                              className="ml-3 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-brand-danger hover:bg-brand-danger-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary-focus"
                            >
                              Delete
                            </button>
                          </td>
                        </tr>
                      }
                    </Fragment>
                  ))
                : null}
            </tbody>
          </table>
        ) : (
          <div
            data-component="no-items-placeholder"
            className="p-6 flex-1 text-center items-center opacity-90 bg-gray-200 rounded my-4"
          >
            No Items
          </div>
        )}
        <ItemForm
          eventName={eventName}
          currentItem={currentItem}
          setCurrentItem={setCurrentItem}
          index={currentItemIndex}
          saveItem={saveItem}
        />
      </div>

      {delModal && (
        <DeleteItemModal
          open={delModal}
          toggleDeleteModal={toggleDeleteModal}
          index={currentItem.index}
          item_id={currentItem.item_id}
          currentItemId={currentItem.item_id}
          deleteItem={deleteItem}
          updateFunction={updateFunction}
        />
      )}
      {/* </div> */}
    </div>
  );
}
