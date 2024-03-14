jQuery(document).ready(function ($) {
  const statusToSVG = /** @type {const} */ ({
    queued: /*html*/ `<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M480 256A224 224 0 1 1 32 256a224 224 0 1 1 448 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM240 112V256c0 5.3 2.7 10.3 7.1 13.3l96 64c7.4 4.9 17.3 2.9 22.2-4.4s2.9-17.3-4.4-22.2L272 247.4V112c0-8.8-7.2-16-16-16s-16 7.2-16 16z"/></svg>`,
    complete: /*html*/ `<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM363.3 203.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L224 297.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"/></svg>`,
    "complete-pro": /*html*/ `<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 0c36.8 0 68.8 20.7 84.9 51.1C373.8 41 411 49 437 75s34 63.3 23.9 96.1C491.3 187.2 512 219.2 512 256s-20.7 68.8-51.1 84.9C471 373.8 463 411 437 437s-63.3 34-96.1 23.9C324.8 491.3 292.8 512 256 512s-68.8-20.7-84.9-51.1C138.2 471 101 463 75 437s-34-63.3-23.9-96.1C20.7 324.8 0 292.8 0 256s20.7-68.8 51.1-84.9C41 138.2 49 101 75 75s63.3-34 96.1-23.9C187.2 20.7 219.2 0 256 0zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>`,
    "complete-manual": /*html*/ `<svg style="width:26px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M128 128a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM269.7 336c80 0 145 64.3 146.3 144H32c1.2-79.7 66.2-144 146.3-144h91.4zM224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3zm457-116.7c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L496 281.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"/></svg>`,
    incomplete: /*html*/ `<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM180.7 180.7c-6.2 6.2-6.2 16.4 0 22.6L233.4 256l-52.7 52.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L256 278.6l52.7 52.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L278.6 256l52.7-52.7c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L256 233.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0z"/></svg>`,
    error: /*html*/ `<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-8.8 0-16 7.2-16 16V272c0 8.8 7.2 16 16 16s16-7.2 16-16V144c0-8.8-7.2-16-16-16zm24 224a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"/></svg>`,
    requested: /*html*/ `<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M296 160c13.3 0 24-10.7 24-24v-8V112 64L480 208 320 352l0-48V288v-8c0-13.3-10.7-24-24-24h-8H192c-70.7 0-128 57.3-128 128c0 8.3 .7 16.1 2 23.2C47.9 383.7 32 350.1 32 304c0-79.5 64.5-144 144-144H288h8zm-8 144v16 32c0 12.6 7.4 24.1 19 29.2s25 3 34.4-5.4l160-144c6.7-6.1 10.6-14.7 10.6-23.8s-3.8-17.7-10.6-23.8l-160-144c-9.4-8.5-22.9-10.6-34.4-5.4s-19 16.6-19 29.2V96v16 16H256 176C78.8 128 0 206.8 0 304C0 417.3 81.5 467.9 100.2 478.1c2.5 1.4 5.3 1.9 8.1 1.9c10.9 0 19.7-8.9 19.7-19.7c0-7.5-4.3-14.4-9.8-19.5C108.8 431.9 96 414.4 96 384c0-53 43-96 96-96h64 32v16z"/></svg>`,
  });

  const statusToLabel = /** @type {const} */ ({
    "complete-pro": "Pro complete",
    "complete-manual": "Manual Complete",
    incomplete: "Incomplete",
    queued: "Queued",
    complete: "Complete",
    error: "Error",
    requested: "Requested",
  });

  let enqueueImagesClicked = [];
  // let optimizationImagesClicked = [];

  function enqueue_image(attachmentID, $status) {
    if ($status && $status.length) {
      $status.find("svg").replaceWith(statusToSVG["queued"]);
      $status.find("span").text(statusToLabel["queued"]);
    }

    $.ajax({
      url: enqueue_vars.ajax_url,
      type: "POST",
      data: {
        action: "imagecomply_enqueue_image",
        attachment_id: attachmentID,
        nonce_2: enqueue_vars.nonce_2,
      },
      success: function (response) {
        // Handle the success response if needed
        console.log(response);
      },
      error: function (xhr, status, error) {
        // Handle the error response if needed
        console.log(error);

        if ($status && $status.length) {
          $status.find("svg").replaceWith(statusToSVG["incomplete"]);
          $status.find("span").text(statusToLabel["incomplete"]);
        }

        enqueueImagesClicked = enqueueImagesClicked.filter(function (
          value,
          index,
          arr
        ) {
          return value !== attachmentID;
        });
      },
    });
  }

  // function enqueue_optimization(attachmentID, $status) {
  //   if ($status && $status.length) {
  //     $status.find("svg").replaceWith(statusToSVG["queued"]);
  //     $status.find("span").text(statusToLabel["queued"]);
  //   }

  //   $.ajax({
  //     url: enqueue_vars.ajax_url,
  //     type: "POST",
  //     data: {
  //       action: "imagecomply_enqueue_optimization",
  //       attachment_id: attachmentID,
  //       nonce_1: enqueue_vars.nonce_1,
  //     },
  //     success: function (response) {
  //       // Handle the success response if needed
  //       console.log(response);
  //     },
  //     error: function (xhr, status, error) {
  //       // Handle the error response if needed
  //       console.log(error);

  //       if ($status && $status.length) {
  //         $status.find("svg").replaceWith(statusToSVG["incomplete"]);
  //         $status.find("span").text(statusToLabel["incomplete"]);
  //       }

  //       enqueueImagesClicked = enqueueImagesClicked.filter(function (
  //         value,
  //         index,
  //         arr
  //       ) {
  //         return value !== attachmentID;
  //       });
  //     },
  //   });
  // }

  $(".imagecomply-enqueue-image").on("click", function (e) {
    e.preventDefault();

    if (enqueueImagesClicked.includes($(this).data("attachment-id"))) {
      return;
    }

    enqueueImagesClicked.push($(this).data("attachment-id"));

    const attachmentID = $(this).data("attachment-id");

    enqueue_image(attachmentID, $(this));
  });

  // /**
  //  * @param {string} mimeType
  //  * @returns {boolean}
  //  */
  // function allowedToOptimize(mimeType) {
  //   return ["image/jpeg", "image/png", "image/gif", "image/jpg"].includes(
  //     mimeType
  //   );
  // }

  if ($(".wp_attachment_details").length) {
    const {
      imagecomply,
      imagecomply_alt_text_status,
      // imagecomply_optimization_status,
    } = enqueue_vars.attachment;

    const attachmentId = new URLSearchParams(window.location.search).get(
      "post"
    );

    /**
     * @type {{
     *  text: typeof statusToLabel[keyof typeof statusToLabel];
     *  svg: typeof statusToSVG[keyof typeof statusToSVG];
     * }}
     */
    let status = {
      text: "",
      svg: "",
    };

    // /**
    //  * @type {{
    //  *  text: typeof statusToLabel[keyof typeof statusToLabel];
    //  *  svg: typeof statusToSVG[keyof typeof statusToSVG];
    //  * }}
    //  */
    // let optimizationStatus = {
    //   text: "",
    //   svg: "",
    // };

    switch (imagecomply_alt_text_status) {
      case "queued":
        status = {
          text: "Queued",
          svg: statusToSVG["queued"],
        };
        break;

      case "complete":
        status = {
          text: "Complete",
          svg: statusToSVG["complete"],
        };
        break;

      case "error":
        status = {
          text: "Error",
          svg: statusToSVG["error"],
        };
        break;

      case "requested":
        status = {
          text: "Requested",
          svg: statusToSVG["requested"],
        };
        break;

      case "complete-pro":
        status = {
          text: "Pro Complete",
          svg: statusToSVG["complete-pro"],
        };
        break;

      case "complete-manual":
        status = {
          text: "Manual",
          svg: statusToSVG["complete-manual"],
        };
        break;

      default:
        status = {
          text: "Incomplete",
          svg: statusToSVG["incomplete"],
        };
        break;
    }

    // switch (imagecomply_optimization_status) {
    //   case "requested":
    //     optimizationStatus = {
    //       text: "Requested",
    //       svg: statusToSVG["requested"],
    //     };
    //     break;

    //   case "queued":
    //     optimizationStatus = {
    //       text: "Queued",
    //       svg: statusToSVG["queued"],
    //     };
    //     break;

    //   case "optimized":
    //     optimizationStatus = {
    //       text: "Complete",
    //       svg: statusToSVG["complete"],
    //     };
    //     break;

    //   default:
    //     optimizationStatus = {
    //       text: "Incomplete",
    //       svg: statusToSVG["incomplete"],
    //     };
    //     break;
    // }

    function getSizeInMb(bytes) {
      return (bytes / 1048576).toFixed(2);
    }

    function commaSeperate(value) {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $(".attachment-alt-text").prepend(
      /*html*/ `
    <style>
      .gradient {
        background-image: linear-gradient(to right, #c084fc, #db2777);
        color: white;
        padding: 15px 10px;
        font-size: 1.125rem;
        font-weight: 600;
        line-height: 1.75;
        padding: 0.75rem 2rem 0.75rem 2rem;
        border: 0;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
      }
      .gradient:hover {
        filter: brightness(1.07);
      }
      .default {
        background-color: #171717;
        color: white;
        padding: 15px 10px;
        font-size: 1.125rem;
        font-weight: 600;
        line-height: 1.75;
        padding: 0.75rem 2rem 0.75rem 2rem;
        border: 0;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all;
      }
      .disabled {
        opacity: 0.15;
        color: #ddd;
        cursor: default;
        user-select: none;
      }

      .banner {
        position: relative;
        overflow: hidden;
        cursor: pointer;
      }

      .banner-no-hover {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        flex-direction: row;
        gap: 15px;
      }
      
      .banner::after {
        content: "View Advanced";
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(to right, #c084fc, #db2777);
        color: white;
        top: -100%;
        left: 0;
        font-weight: bold;
        transition: transform 300ms ease-in-out;
      }
      
      .banner:hover::after {
        transform: translateY(100%);
      }
      
      .banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
      }
      .memory-saved {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 15px;
      }

      .link {
        border-bottom: 2px solid #db2777;
        background: linear-gradient(to right, #a855f7, #db2777);
        background: -webkit-gradient(linear, left top, right top, from(#a855f7), to(#db2777));

        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        cursor: pointer;
        transition: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 1;
      }

      .link:hover{
        opacity: 0.8;
        color: transparent;
        text-decoration: none;
      }

      .stats {
        background-color: #ffffff;
      }
      table {
        width: 100%;
        font-family:Arial, Helvetica, sans-serif;
        color: #666;
        font-size: 12px;
        text-shadow: 1px 1px 0px #fff;
        border:#ccc 1px solid;
      
        border-radius: 0 0 3px 3px;
      
        -moz-box-shadow: 0 1px 2px #d1d1d1;
        -webkit-box-shadow: 0 1px 2px #d1d1d1;
      }
      table th {
        padding:21px 25px 22px 25px;
        border-top:1px solid #fafafa;
        border-bottom:1px solid #e0e0e0;
      
        background: #ededed;
        background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
        background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
      }
      table tr:first-child th:first-child {
        -moz-border-radius-topleft:3px;
        -webkit-border-top-left-radius:3px;
        border-top-left-radius:3px;
      }
      table tr:first-child th:last-child {
        -moz-border-radius-topright:3px;
        -webkit-border-top-right-radius:3px;
        border-top-right-radius:3px;
      }
      table tr {
        text-align: center;
        padding-left:20px;
      }
      table td:first-child {
        text-align: left;
        padding-left:20px;
        border-left: 0;
      }
      table td {
        padding:18px;
        border-top: 1px solid #ffffff;
        border-bottom:1px solid #e0e0e0;
        border-left: 1px solid #e0e0e0;
      
        background: #fafafa;
        background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
        background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
      }
      table tr.even td {
        background: #f6f6f6;
        background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
        background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
      }
      table tr:last-child td {
        border-bottom:0;
      }
      table tr:last-child td:first-child {
        -moz-border-radius-bottomleft:3px;
        -webkit-border-bottom-left-radius:3px;
        border-bottom-left-radius:3px;
      }
      table tr:last-child td:last-child {
        -moz-border-radius-bottomright:3px;
        -webkit-border-bottom-right-radius:3px;
        border-bottom-right-radius:3px;
      }
    </style>
		<div style='margin-bottom:10px;margin-top:40px;font-size:22px;'>
			<div style='width:min(500px, 100%);display:inline-block;padding:40px;border-radius:5px;background:#fff;border:1px solid rgb(0,0,0,0.2);'>
				<div style='font-weight:bold;font-size:30px;margin-bottom:30px;'>ImageComply</div>
				<div style='display:flex;gap:30px;flex-direction:column;'>
					<div>
						<div style='font-weight:bold;margin-bottom:10px;'>Alt Text</div>
						<div class="alt-text-status-container" style='display:flex;gap:10px;align-items:center;'>${status.svg} <span class="status">${status.text}</span></div>
					</div>
` /*
          <div>
						<div style='font-weight:bold;margin-bottom:10px;'>Optimized</div>
						<div class="optimization-status-container" style='display:flex;gap:10px;align-items:center;'>${
              optimizationStatus.svg
            } <span class="status">${optimizationStatus.text}</span></div>
					</div>
          ${
            imagecomply?.["old_path"]
              ? /*html*/ /* `
              <div>
              <div style='font-weight:bold;margin-bottom:10px;'>Restore</div>
                <div class="optimization-status-container" style='display:flex;gap:20px;flex-direction:column;'>
                  <span>Need to view the original image? View it <a class="link" href="${imagecomply?.old_path}" target="_blank">here</a>.</span>
                </div>
              </div>
					`
              : ""
          }
*/ +
        /*html*/ `
					${
            imagecomply?.generated_alt
              ? /*html*/ `
						<div>
							<div style='font-weight:bold;margin-bottom:10px;'>Generated Alt Text</div>
							<div>
								<code>${imagecomply.generated_alt}</code>
							</div>
						</div>
					`
              : ""
          }
          <div style="display:flex;flex-direction:column;gap:8px;margin-top:20px;justify-content:center;">
            <button data-attachment-id="${
              attachmentId ?? ""
            }" class="imagecomply-enqueue-alt gradient">Generate Alt Text</button>
            ${
              /*
              imagecomply_optimization_status === "optimized" ||
              !allowedToOptimize(imagecomply?.mime ?? "")
                ? '<div class="default disabled" style="text-align:center;">Optimize Image</div>'
                : `<button data-attachment-id="${
                    attachmentId ?? ""
                  }" style="text-align:center;" class="imagecomply-enqueue-optimization default">Optimize Image</button>`
                */ ""
            }
          </div>
				</div>
			</div>
		</div>
    
		`
    );
    // ${
    //   imagecomply?.["memory_saved"]
    //     ?
    //       Math.floor(imagecomply?.["memory_saved"]) > 0
    //       ? /* html */ `
    //   <div style='margin-bottom:40px;font-size:16px;display:flex;flex-direction:column;width:581px;'>
    //     <div class="banner" style='display:flex;border-radius:5px 5px 5px 5px;background:#fff;border:1px solid rgb(0,0,0,0.2);overflow:hidden;position:relative;height:60px;border-left:4px solid #c084fc;align-items:center;padding: 0 38px;max-width:582px;'>
    //       <div class="secret"></div>
    //       <span class="memory-saved">
    //         <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
    //           <path d="M48 24C48 10.7 37.3 0 24 0S0 10.7 0 24V64 350.5 400v88c0 13.3 10.7 24 24 24s24-10.7 24-24V388l80.3-20.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30V66.1c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L48 52V24zm0 77.5l96.6-24.2c27-6.7 55.5-3.6 80.4 8.8c54.9 27.4 118.7 29.7 175 6.8V334.7l-24.4 9.1c-33.7 12.6-71.2 10.7-103.4-5.4c-48.2-24.1-103.3-30.1-155.6-17.1L48 338.5v-237z"/>
    //         </svg>
    //           Optimization has reduced this image's size by ${Math.floor(
    //             imagecomply?.["memory_saved"]
    //           )}%.
    //       </span>
    //     </div>
    //     <div class="stats" style="display:none;width:581px;cursor:pointer;">
    //       <table cellspacing='0'>
    //         <thead>
    //           <tr>
    //             <th>Item</th>
    //             <th>Size (Bytes)</th>
    //             <th>Size (Mb)</th>
    //           </tr>
    //         </thead>
    //         <tbody>

    //           <tr>
    //             <td>Before ImageComply</td>
    //             <td>${commaSeperate(imagecomply?.["old_file_size"])}</td>
    //             <td>${getSizeInMb(imagecomply?.["old_file_size"])}</td>
    //           </tr>

    //           <tr class="even">
    //             <td>After ImageComply</td>
    //             <td>${commaSeperate(imagecomply?.["new_file_size"])}</td>
    //             <td>${getSizeInMb(imagecomply?.["new_file_size"])}</td>
    //           </tr>
    //           <tr style="height: 56px;">
    //             <td></td>
    //             <td></td>
    //             <td></td>
    //           </tr>

    //           <tr style="max-height: 56px;font-weight: bold;">
    //             <td>Total</td>
    //             <td></td>
    //             <td>- ${Math.floor(imagecomply?.["memory_saved"])}%</td>
    //           </tr>

    //         </tbody>
    //       </table>
    //     </div>
    //   </div>
    //   <script>
    //     let clicked = false;

    //     function click(){
    //       clicked = !clicked;

    //       const bannerElement = document.querySelector(".banner");
    //       const statsElement = document.querySelector(".stats");

    //       if(clicked){
    //         statsElement.style.display = "block";

    //         bannerElement.style.borderRadius = "5px 5px 0 0";
    //         bannerElement.style.borderBottom = "0";
    //       }
    //       else{
    //         statsElement.style.display = "none";

    //         bannerElement.style.borderRadius = "5px 5px 5px 5px";
    //         bannerElement.style.borderBottom = "1px solid rgb(0,0,0,0.2)";
    //       }
    //     }

    //     document.querySelector(".banner").addEventListener("click", click);
    //     document.querySelector(".stats").addEventListener("click", click);
    //   </script>
    //   `
    //       : ""
    //     : ""
    // }
  }

  /* 
    Button events
  */
  $(".imagecomply-enqueue-alt").on("click", function (e) {
    e.preventDefault();

    if (enqueueImagesClicked.includes($(this).data("attachment-id"))) {
      return;
    }

    enqueueImagesClicked.push($(this).data("attachment-id"));

    const attachmentID = $(this).data("attachment-id");

    const response = confirm(
      "Are you certain you wish to regenerate this alt text? Doing so will consume an additional token and the resulting alt text may or may not be an improvement."
    );

    if (
      response
    ) {
      $(".alt-text-status-container").html(/*html*/ `
        ${statusToSVG["queued"]}
        <span class="status">Queued</span></div>
      `);

      enqueue_image(attachmentID, $(this));
    }
  });

  // $(".imagecomply-enqueue-optimization").on("click", function (e) {
  //   e.preventDefault();

  //   if (
  //     optimizationImagesClicked.includes($(this).data("attachment-id")) ||
  //     $(this).hasClass("disabled")
  //   ) {
  //     return;
  //   }

  //   optimizationImagesClicked.push($(this).data("attachment-id"));

  //   const attachmentID = $(this).data("attachment-id");

  //   $(this).addClass("disabled");
  //   $(".optimization-status-container").html(/*html*/ `
  //       ${statusToSVG["queued"]}
  //       <span class="status">Queued</span></div>
  //   `);

  //   enqueue_optimization(attachmentID, $(this));
  // });
});
