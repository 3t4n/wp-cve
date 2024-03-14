import React, { useState, FC, useEffect } from "react";
import { getNonce } from "../Helpers";
import { toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import Drag from "../core/Drag";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";

import Card from "../core/Card";
import WordPress from "./Integrations/WordPress";
import WooCommerce from "./Integrations/WooCommerce";
import ContactForm7 from "./Integrations/ContactForm7";
import WPForms from "./Integrations/WPForms";
import BuddyPress from "./Integrations/BuddyPress";
import Elementor from "./Integrations/Elementor";
import GravityForms from "./Integrations/GravityForms";
import Formidable from "./Integrations/Formidable";
import MailChimp from "./Integrations/MailChimp";
import Forminator from "./Integrations/Forminator";
import WpDiscuz from "./Integrations/WpDiscuz";
import BbPress from "./Integrations/BbPress";
import HappyForms from "./Integrations/HappyForms";
import WPUF from "./Integrations/WPUF";
import JetpackForms from "./Integrations/JetPack";

type Props = {
  store: {
    integrations: {};
    fields: [];
  };
  setStore: any;
};

const Integrations: FC<Props> = ({ store, setStore }) => {
  const [integrationOrder, setIntegrationOrder] = useState(() => {
    const storedIntegrationOrder = localStorage.getItem("integrationOrder");
    const defaultIntegrationOrder = [
      "wordpress",
      "woocommerce",
      "contactform7",
      "wpforms",
      "buddypress",
      "elementor",
      "gravityforms",
      "formidable",
      "mailchimp",
      "forminator",
      "wpdiscuz",
      "bbpress",
      "happyforms",
      "wpuf",
      "jetpack",
    ];
    if (storedIntegrationOrder) {
      const parsedIntegrationOrder = JSON.parse(storedIntegrationOrder);
      const newIntegrations = defaultIntegrationOrder.filter(
        (integration) => !parsedIntegrationOrder.includes(integration)
      );
      const updatedIntegrationOrder = [
        ...parsedIntegrationOrder,
        ...newIntegrations,
      ];
      return updatedIntegrationOrder;
    }
    return defaultIntegrationOrder;
  });

  useEffect(() => {
    localStorage.setItem("integrationOrder", JSON.stringify(integrationOrder));
  }, [integrationOrder]);

  const handleSingleIntegration = (context, e): void => {
  
    if (e.target.checked && context.toString() && store.fields?.[context]) {
      const newFields = {
        ...store,
        fields: {
          ...store.fields,
          [context]: [...store.fields[context], ...[e.target.name]],
        },
      };
      setStore(newFields);
    } else if (!store.fields?.[context]) {
      const newFields = {
        ...store,
        fields: {
          ...store.fields,
          [context]: [e.target.name],
        },
      };
      setStore(newFields);
    } else {
      const newFields = {
        ...store,
        fields: {
          ...store.fields,
          [context]: store.fields[context].filter(item => item !== e.target.name),
        },
      };
      setStore(newFields);
    }
  };
  
  const handleDragEnd = (result: any) => {
    if (!result.destination) return;

    const { source, destination } = result;
    const newIntegrationOrder = Array.from(integrationOrder);
    const [removed] = newIntegrationOrder.splice(source.index, 1);
    newIntegrationOrder.splice(destination.index, 0, removed);
    setIntegrationOrder(newIntegrationOrder);
  };

  useEffect(() => {
    wp.ajax.send("update_store", {
      data: {
        nonce: getNonce(),
        store: JSON.stringify(store),
      },
      success(response: any) {
        if (response && response.message) {
          const { message } = response;
          const { data } = response;
          if (
            Array.isArray(Object.values(data)) &&
            Object.values(data)[0] === "Turned ON for"
          ) {
            toast.success(message);
          } else {
            toast.error(message);
          }
        }
      },
      error(err: any) {
        console.error(err);
        toast.error(err);
      },
    });
  }, [store]);

  return (
    <div className="ect-integrations">
      <div className="integrations-body">
        <DragDropContext onDragEnd={handleDragEnd}>
          <Droppable droppableId="integrations">
            {(provided: any) => (
              <div {...provided.droppableProps} ref={provided.innerRef}>
                {integrationOrder.map((integration: string, index: number) => {
                  let Component: any;
                  switch (integration) {
                    case "wordpress":
                      Component = WordPress;
                      break;
                    case "woocommerce":
                      Component = WooCommerce;
                      break;
                    case "contactform7":
                      Component = ContactForm7;
                      break;
                    case "wpforms":
                      Component = WPForms;
                      break;
                    case "buddypress":
                      Component = BuddyPress;
                      break;
                    case "elementor":
                      Component = Elementor;
                      break;
                    case "gravityforms":
                      Component = GravityForms;
                      break;
                    case "formidable":
                      Component = Formidable;
                      break;
                    case "mailchimp":
                      Component = MailChimp;
                      break;
                    case "forminator":
                      Component = Forminator;
                      break;
                    case "wpdiscuz":
                      Component = WpDiscuz;
                      break;
                    case "bbpress":
                      Component = BbPress;
                      break;
                    case "happyforms":
                      Component = HappyForms;
                      break;
                    case "wpuf":
                      Component = WPUF;
                      break;                    
                    case "jetpack":
                      Component = JetpackForms;
                      break;
                    default:
                      Component = null;
                      break;
                  }

                  if (!Component) return null;

                  return (
                    <Draggable
                      key={integration}
                      draggableId={integration}
                      index={index}
                    >
                      {(provided: any) => (
                        <div
                          {...provided.draggableProps}
                          {...provided.dragHandleProps}
                          ref={provided.innerRef}
                        >
                          <Card colored>
                            <div className="single-integration">
                              <Drag />
                              <Component
                                handleSingleIntegration={(e) =>
                                  handleSingleIntegration(integration, e)
                                }
                                store={store}
                                setStore={setStore}
                              />
                            </div>
                          </Card>
                        </div>
                      )}
                    </Draggable>
                  );
                })}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
        </DragDropContext>
      </div>
    </div>
  );
};

export default Integrations;
