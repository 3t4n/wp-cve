export async function batchFetch() {
  this.syncRequired = [];
  this.stopFetchBtn = true;

  const fetchData = async page => {
    if (this.stopFlag) {
      return;
    }
    this.errors = [];

    const batchSize = Number(data.batch_size);

    let offset = (page - 1) * batchSize;

    let postData = {
      action: "bulk_fetch",
      nonce: data.nonce,
      batchSize: batchSize,
      page: page,
      offset: offset,
      totalPages: this.total_pages,
    };

    try {
      const response = await axios.post(ajaxurl, Qs.stringify(postData));
      // console.log(response.data.data.posts);

      response.data.data.posts.forEach(item => {
        this.syncRequired.push(item);
      });

      // console.log(this.syncRequired);
      this.fetchingProgress = Math.round(response.data.data.progress);

      if (page < this.total_pages) {
        // Wait for half second before fetching the next batch of data
        await new Promise(resolve => setTimeout(resolve, 500));
        // Call the function recursively to fetch the next batch of data
        await fetchData(page + 1);
      } else {
        this.sync = true;
        this.disabled = false;
        this.stopFetchBtn = false;
      }
    } catch (error) {
      console.log(error);
      if (error && error.response) {
        console.log(error.response);
        console.log(error.response.data);
      }
      this.errors =
        error.response && error.response.data && error.response.data
          ? error.response.data.data.errors
          : "An error occurred";
      this.disabled = false;
      this.stopStoreBtn = false;

      this.disabled = false;
      this.stopFetchBtn = false;
      this.sync = false;
    }
  };

  if (this.total_pages > 0) {
    await fetchData(1); // Call the function to fetch the first batch of data
  }
}

export async function bulkAdd() {
  if (!this.syncRequired.length) {
    this.logs.unshift({
      status: false,
      data: "There is nothing to sync. Try to fetch items again",
    });
    console.log(this.logs);
    return;
  }

  this.disabled = true;
  this.stopStoreBtn = true;
  let count = 0;
  let totalItems = this.syncRequired.length;

  const storeItem = async itemIndex => {
    if (this.stopFlag) {
      return;
    }

    // Set post variable to passed item
    let post = this.syncRequired[itemIndex];

    // Make sure post title is not empty
    if (!post.post_title) {
      this.logs.unshift({
        status: false,
        data: "Post with ID " + post.ID + " doesn't have post title. Skipped",
      });

      await storeItem(itemIndex + 1);
    }

    // Assign post data for Axios request
    let postData = {
      action: "bulk_add",
      nonce: data.nonce,
      post_id: post.ID,
      post_title: post.post_title,
    };

    try {
      const response = await axios.post(ajaxurl, Qs.stringify(postData));

      if (response && response.data && response.data.success === true) {
        response.data.created_at = getCurrentDateTime();
        //PUSH LINK (ON TOP) TO CONSOLE LOG ARRAY
        this.logs.unshift(response.data);

        // Calculate progress bar
        count++;
        this.storingProgress = Math.round((count / totalItems) * 100);
      }

      if (itemIndex < this.syncRequired.length - 1) {
        // Wait for half second before fetching the next batch of data
        await new Promise(resolve => setTimeout(resolve, 500));

        // Call the function recursively to fetch the next batch of data
        await storeItem(itemIndex + 1);
      } else {
        // Set syncRequired items to empty array
        this.syncRequired = [];
        // Hide Stop Button for Sync Now
        this.stopStoreBtn = false;
        // SET LAST SYNC DATE in WORDPRESS OPTIONS
        let postData = {
          action: "sync_date",
          nonce: data.nonce,
          alldone: true,
        };

        axios.post(ajaxurl, Qs.stringify(postData));
      }
    } catch (error) {
      console.log(error.response);
      if (error && error.response) {
        console.log(error.response);
        console.log(error.response.data);
      }
      this.errors =
        error.response && error.response.data && error.response.data
          ? error.response.data.data.errors
          : "An error occurred";
      this.disabled = false;
      this.stopStoreBtn = false;
    }
  };

  if (this.syncRequired.length > 0) {
    await storeItem(0); // Call the function to fetch the first batch of data
  }
}

export function bulkStop() {
  this.disabled = false;
  this.stopStoreBtn = false;
  this.syncRequired = [];

  // SET LAST SYNC DATE in WORDPRESS OPTIONS
  let postData = {
    action: "sync_date",
    nonce: data.nonce,
    alldone: true,
  };

  axios.post(ajaxurl, Qs.stringify(postData));
}

export function deleteItem(id) {
  const index = this.syncRequired.findIndex(item => item.ID === id);
  this.syncRequired.splice(index, 1);
}

export async function deleteLogItem(id) {
  if (!id) {
    console.log("log id not found");
    return;
  }

  this.errors = [];

  var result = confirm("Are you sure you want to delete?");

  // Check if the result if confirmed
  if (result) {
    console.log("detete focus keyword from post " + id);

    let postData = {
      action: "delete_item",
      nonce: data.nonce,
      id: id,
    };

    await axios
      .post(ajaxurl, Qs.stringify(postData))
      .then(response => {
        console.log(response.data);

        if (response.data.success == true) {
          // Find Index
          let objIndex = this.sync_logs.findIndex(obj => obj.post_id == id);
          // Delete item from Current Array
          this.sync_logs.splice(objIndex, 1);
        }
      })
      .catch(error => {
        console.log(error);
        if (error && error.response) {
          console.log(error.response);
          console.log(error.response.data);
          this.errors =
            error.response && error.response.data && error.response.data
              ? error.response.data.data.errors
              : "An error occurred";
        }
      });
  }
}

export async function bulkDeleteLogs() {
  if (!this.ids.length) {
    console.log("Please select some logs to continue");
    this.errors.push("Please select some logs to continue");
    return;
  }

  this.errors = [];

  var result = confirm("Are you sure you want to delete?");

  // Check if the result if confirmed
  if (result) {
    this.disabled = true;
    this.stopDeleteBtn = true;
    let count = 0;
    let totalItems = this.ids.length;

    const deleteItem = async itemIndex => {
      if (this.stopFlag) {
        return;
      }

      // Set post variable to passed item
      let post_id = this.ids[itemIndex];

      console.log(post_id);

      // Assign post data for Axios request
      let postData = {
        action: "delete_item",
        nonce: data.nonce,
        id: post_id,
      };

      try {
        const response = await axios.post(ajaxurl, Qs.stringify(postData));

        if (response && response.data && response.data.success === true) {
          let objIndex = this.sync_logs.findIndex(
            obj => obj.post_id == post_id
          );
          // Delete item from Current Array
          this.sync_logs.splice(objIndex, 1);

          // Calculate progress bar
          count++;
          this.deletingProgress = Math.round((count / totalItems) * 100);
        }

        if (itemIndex < this.ids.length - 1) {
          // Wait for half second before fetching the next batch of data
          await new Promise(resolve => setTimeout(resolve, 500));

          // Call the function recursively to fetch the next batch of data
          await deleteItem(itemIndex + 1);
        } else {
          // Set deleting ids to empty array
          this.ids = [];
          this.disabled = false;
          this.stopDeleteBtn = false;
        }
      } catch (error) {
        console.log(error);
        if (error && error.response) {
          console.log(error.response);
          console.log(error.response.data);
          this.errors =
            error.response && error.response.data && error.response.data
              ? error.response.data.data.errors
              : "An error occurred";
        }
        this.disabled = false;
        this.stopDeleteBtn = false;
      }
    };

    if (this.ids.length > 0) {
      await deleteItem(0); // Call the function to fetch the first batch of data
    }
  }
}

export function selectAll(e) {
  this.ids = [];
  if (e.target.checked === true) {
    this.sync_logs.forEach(item => {
      this.ids.push(item.post_id);
    });
    // console.log(this.paginate.items.list)
    console.log(this.ids);
  } else {
    this.ids.splice(0, this.sync_logs.length);
  }
}

export function convertTimestamp(timestamp) {
  const date = new Date(timestamp * 1000); // Multiply by 1000 to convert from seconds to milliseconds
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  const month = months[date.getMonth()];
  const day = date.getDate();
  const year = date.getFullYear();
  const hours = date.getHours();
  const minutes = date.getMinutes();
  const seconds = date.getSeconds();

  return `${month} ${day}, ${year}, ${hours}:${minutes}:${seconds}`;
}

function getCurrentDateTime() {
  const currentDate = new Date();
  const year = currentDate.getFullYear();
  const month = String(currentDate.getMonth() + 1).padStart(2, "0");
  const day = String(currentDate.getDate()).padStart(2, "0");
  const hours = String(currentDate.getHours()).padStart(2, "0");
  const minutes = String(currentDate.getMinutes()).padStart(2, "0");
  const seconds = String(currentDate.getSeconds()).padStart(2, "0");

  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}
