// Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyCBZlm015kYnH78r0F7Q3y3j6L0EWKfqdc",
      authDomain: "chishimba.firebaseapp.com",
      projectId: "chishimba",
      storageBucket: "YOUR_PROJECT_ID.appspot.com",
      messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
      appId: "chishimba"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    // Initialize Firestore
    const db = firebase.firestore();

    // Function to set up real-time listener for counting documents
    function setupRealTimeCountListener(collectionName, whereField, whereValue, elementId) {
      try {
          let query = db.collection(collectionName);
          if (whereField && whereValue) {
              query = query.where(whereField, '==', whereValue);
          }
  
          // Attach real-time listener
          query.onSnapshot(snapshot => {
              const count = snapshot.size;
              document.getElementById(elementId).innerHTML = count;
          });
  
      } catch (error) {
          console.error(`Error setting up real-time listener for ${collectionName}: `, error);
      }
  }
  
  // Function to set up a real-time listener for collection data display
  async function setupRealTimeDataListener(collectionName, id) {
      try {
          const query = db.collection(collectionName);
          query.onSnapshot(async snapshot => {  // Make this async to use await inside it
              const dataContainer = document.getElementById(id);
              dataContainer.innerHTML = ''; // Clear previous content
  
              for (const doc of snapshot.docs) {
                  const data = doc.data();
                  const docDiv = document.createElement('li');
                  docDiv.className = "list-group-item";
                  docDiv.id = doc.id; // Set unique ID for each element
  
                  // Format Firestore Timestamp
                  const timestamp = data.added_date;
                  const date = timestamp.toDate();
                  const humanReadableTime = date.toLocaleString('en-US', {
                      year: 'numeric', month: 'short', day: 'numeric',
                      hour: 'numeric', minute: 'numeric', second: 'numeric',
                      hour12: false
                  });
  
                  // Function to fetch user data
                  async function fetchUserData(doc) {
                      try {
                          const userDoc = await db.collection('users').doc(doc.cus_id).get();
                          let userName = "Unknown User"; // Default value in case user is not found
  
                          if (userDoc.exists) {
                              const userData = userDoc.data();
                              userName = userData || "No Name";
                          }else
                          {
                              userDoc = await db.collection('users').doc(doc.user_id).get();
                             userName = "Unknown User"; // Default value in case user is not found
                            if (userDoc.exists) {
                                const userData = userDoc.data();
                                userName = userData || "No Name";
                            }

                          } 
  
                          return userName;
                      } catch (error) {
                          console.log(error.toString());
                          return "Unknown User";
                      }
                  }
  
                  // Fetch user data with await
                  let userData = await fetchUserData(data);
  
                  // Populate UI element with data
                  docDiv.innerHTML = `
                      <div class="row align-items-center no-gutters">
                          <div class="col me-2">
                              <h6 class="mb-0">${userData.first_name} <strong class="text-${data.order_status == 'approved' ? 'success' : 'warning'}">${data.order_status}</strong></h6>
                              <span class="text-xs">${humanReadableTime}</span>
                          </div>
                          <div class="col-auto">
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="formCheck-${doc.id}">
                                  <label class="form-check-label" for="formCheck-${doc.id}"></label>
                              </div>
                          </div>
                      </div>
                  `;
                  dataContainer.appendChild(docDiv);
              }
          });
      } catch (error) {
          console.error(`Error setting up real-time listener for ${collectionName}: `, error);
      }
  }

//Load approvals
async function setupRealTimeLoadApprovalsListener(order_status, token) {
  try {
    let query = db.collection('loads').where('status', '==', order_status);

    if (order_status == "all") {
      //For getting all the orders
       query = db.collection('loads').where('status', '!=', 'yess');
    }
      query.onSnapshot(async snapshot => {
          const pendingOrdersContainer = document.getElementById('pendingOrders');
          pendingOrdersContainer.innerHTML = ''; // Clear previous content
          
          let loopCount = 0;
          for (const doc of snapshot.docs) {
            loopCount++;
              const data = doc.data();
              
              // Create a table row element
              const row = document.createElement('tr');

              // Fetch user data function
              async function fetchUserData(doc) {
                  try {
                      const userDoc = await db.collection('users').doc(doc.user_id).get();
                      let userName = "Unknown User"; // Default value in case user is not found

                      if (userDoc.exists) {
                          const userData = userDoc.data();
                          userName = userData || "No Name";
                      }

                      return userName;
                  } catch (error) {
                      console.log(error.toString());
                      return "Unknown User";
                  }
              }
              
              
               // Format Firestore Timestamp
               const timestamp = data.added_date;
               const date = timestamp.toDate();
               const humanReadableTime = date.toLocaleString('en-US', {
                   year: 'numeric', month: 'short', day: 'numeric',
                   hour: 'numeric', minute: 'numeric', second: 'numeric',
                   hour12: false
               });
              // Fetch user data with await
              let userData = await fetchUserData(data);

              // Populate the row with data
              row.innerHTML = `
                  <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">${userData.first_name} ${userData.last_name}</td>
                  <td>${data.status || 'N/A'}</td>
                  <td>${humanReadableTime || 'N/A'}</td>
                  <td><!-- Button trigger modal -->
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal${loopCount}">
                    Details
                  </button>
                  
                  <!-- Modal -->
                  <div class="modal fade" id="exampleModal${loopCount}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Load Details</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>
                          Customer Name:  <b> ${userData.first_name} ${userData.last_name} </b>
                          </p>
                          <p>
                          Phone Number: <b> ${userData.phone_number} </b>
                          </p>
                          <p>
                          Load Name: <b> ${data.load_name} </b>
                          </p>
                          <p>
                          Rate: <b> ${data.rate} ${data.currency} </b>
                          </p>
                          
                         
                          <h3> Location</h3>
                           <p>
                          Pick up Location: <b> ${data.pickup_loc_name} </b>
                          </p>
                           <p>
                          Drop Off Location: <b> ${data.dropoff_loc_name} </b>
                          </p>
                          <div class="card" id="map"></div>

                          <hr>
                          <div class="card">
                          <div class="card-header">
                          <div class="card-title">
                          Decision 
                          </div>
                          <form method="post" action="">
                          <input hidden type="text" name="doc_id" value="${doc.id}" >
                          <input hidden type="text" name="_token" value="${token}" >


                         
                          <button name="approve_btn" class="btn btn-sm btn-primary" type="submit">Approve Load</button>

                          <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectOrderModal${loopCount}">Reject Load</button>

                          </form>
                          </div>
                          </div>

 
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>                     <!-- Rejection Modal -->
                    <div class="modal fade" id="rejectOrderModal${loopCount}" tabindex="-1" aria-labelledby="rejectOrderLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectOrderLabel">Cancel Load</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="post" action="">
                              <input type="hidden" name="doc_id" value="${doc.id}">
                              <input type="hidden" name="_token" value="${token}">
                              <input hidden type="text" name="fcm_token" value="${userData.fcm_token}" >
<input hidden type="text" name="phone_number" value="${userData.phone_number}" >
                             


                              <div class="mb-3">
                                <label for="rejectionReason" class="form-label">Reason for Rejection:</label>
                                <textarea required class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required></textarea>
                              </div>
                              
                              <button name="cancel_btn" class="btn btn-danger" type="submit">Submit</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                `;

                 
              // Append the row to the table body
              pendingOrdersContainer.appendChild(row);

              mapboxgl.accessToken = 'pk.eyJ1IjoiY29tZm9ydGNoYW1iZXNoaSIsImEiOiJjbHFpZDN4dXMxbmVsMmxvNGJlZDRmaGxjIn0.GVfGQ6EbVkDWGJHce0P8fA';
              var map = new mapboxgl.Map({
                  container: 'map',
                  style: 'mapbox://styles/mapbox/streets-v11',
                  center: [data.load_longitude, data.load_latitude], // Corrected order for Lusaka coordinates
                  zoom: 10
              });
      
              var marker = new mapboxgl.Marker()
                  .setLngLat([data.load_longitude, data.load_latitude]) // Corrected order for marker coordinates
                  .addTo(map);
          }
      });
  } catch (error) {
      console.error(`Error setting up real-time listener for pending orders: `, error);
  }
}

  async function setupRealTimePendingOrdersListener(order_status, token) {
    try {
      let query = db.collection('orders').where('order_status', '==', order_status);

      if (order_status == "all") {
        //For getting all the orders
         query = db.collection('orders').where('order_status', '!=', 'yess');
      }
        query.onSnapshot(async snapshot => {
            const pendingOrdersContainer = document.getElementById('pendingOrders');
            pendingOrdersContainer.innerHTML = ''; // Clear previous content
            
            let loopCount = 0;
            for (const doc of snapshot.docs) {
              loopCount++;
                const data = doc.data();
                
                // Create a table row element
                const row = document.createElement('tr');

                // Fetch user data function
                async function fetchUserData(doc) {
                    try {
                        const userDoc = await db.collection('users').doc(doc.cus_id).get();
                        let userName = "Unknown User"; // Default value in case user is not found

                        if (userDoc.exists) {
                            const userData = userDoc.data();
                            userName = userData || "No Name";
                        }

                        return userName;
                    } catch (error) {
                        console.log(error.toString());
                        return "Unknown User";
                    }
                }
                
                
                 // Format Firestore Timestamp
                 const timestamp = data.added_date;
                 const date = timestamp.toDate();
                 const humanReadableTime = date.toLocaleString('en-US', {
                     year: 'numeric', month: 'short', day: 'numeric',
                     hour: 'numeric', minute: 'numeric', second: 'numeric',
                     hour12: false
                 });
                // Fetch user data with await
                let userData = await fetchUserData(data);

                // Populate the row with data
                row.innerHTML = `
                    <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">${userData.first_name} ${userData.last_name}</td>
                    <td>${data.order_status || 'N/A'}</td>
                    <td>${data.description || 'N/A'}</td>
                    <td>${humanReadableTime || 'N/A'}</td>
                    <td><!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal${loopCount}">
                      Details
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal${loopCount}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>
                            Customer Name:  <b> ${userData.first_name} ${userData.last_name} </b>
                            </p>
                            <p>
                            Phone Number: <b> ${userData.phone_number} </b>
                            </p>
                            <p>
                            Commodity: <b> ${data.commodity} </b>
                            </p>
                            <p>
                            Rate: <b> ${data.amount} ${data.currency} </b>
                            </p>
                            <p>
                            Distance: <b> ${data.distance}  KM</b>
                            </p>
                            <h3>Location</h3>
                            <div class="card" id="map"></div>
                            <br>
                            ${data.reject_reason && data.reject_reason !== "" && data.reject_reason !== "NE"  ? '<div class="card"> <div class="card-header text-danger"> Rejection reason </div> <div class="card-body"> '+data.reject_reason+' </div> </div>' : ""}
                              `
                              ;                           
                             if (data.order_status != "cancelled") {
                              
                            `
                            
                            <div class="card">
                            <div class="card-header">
                            <div class="card-title">
                            Approve Order
                            </div>
                            <form method="post" action="">
                            <input hidden type="text" name="doc_id" value="${doc.id}" >
                            <input hidden type="text" name="_token" value="${token}" >
                            <input hidden type="text" name="fcm_token" value="${userData.fcm_token}" >
                            <input hidden type="text" name="phone_number" value="${userData.phone_number}" >


                            <input ${data.driver_id != '' ? 'readonly' : ''}   value="${data.driver_id != '' ? data.truck_id : ''}" placeholder="Truck ID" type="text" name="license_number" class="form-control"  />
                            <p>
                            <a href="/admin/trucks/1">Click here to check available trucks</a>
                            </p>
                            <button name="approve_btn" class="btn btn-sm btn-primary" type="submit">Assign</button>

                            <hr>
                            <h4 class="text-center">OR</h4>
                           
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectOrderModal${loopCount}">Cancel Order</button>
                            </form>
                            </div>
                            </div>

   
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div> 
                    <!-- Rejection Modal -->
                    <div class="modal fade" id="rejectOrderModal${loopCount}" tabindex="-1" aria-labelledby="rejectOrderLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectOrderLabel">Cancel Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="post" action="">
                              <input type="hidden" name="doc_id" value="${doc.id}">
                              <input type="hidden" name="_token" value="${token}">
                              <input hidden type="text" name="fcm_token" value="${userData.fcm_token}" >
<input hidden type="text" name="phone_number" value="${userData.phone_number}" >



                              <div class="mb-3">
                                <label for="rejectionReason" class="form-label">Reason for Cancellation:</label>
                                <textarea required class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required></textarea>
                              </div>
                              
                              <button name="cancel_btn" class="btn btn-danger" type="submit">Submit</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>`;

                             }

        `
    </td>
                   
                `;

                   
                // Append the row to the table body
                pendingOrdersContainer.appendChild(row);

                mapboxgl.accessToken = 'pk.eyJ1IjoiY29tZm9ydGNoYW1iZXNoaSIsImEiOiJjbHFpZDN4dXMxbmVsMmxvNGJlZDRmaGxjIn0.GVfGQ6EbVkDWGJHce0P8fA';
                var map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: [data.initial_longitude, data.initial_latitude], // Corrected order for Lusaka coordinates
                    zoom: 10
                });
        
                var marker = new mapboxgl.Marker()
                    .setLngLat([data.initial_longitude, data.initial_latitude]) // Corrected order for marker coordinates
                    .addTo(map);
            }
        });
    } catch (error) {
        console.error(`Error setting up real-time listener for pending orders: `, error);
    }
}


//Real-time load owners load
async function setupRealTimePendingLoadOwnersListener(token) {
    try {
      let query = db.collection('users').where('user_type', '==', "Customer");

   
        query.onSnapshot(async snapshot => {
            const pendingOrdersContainer = document.getElementById('loadOwnersList');
            pendingOrdersContainer.innerHTML = ''; // Clear previous content

            for (const doc of snapshot.docs) {
                const data = doc.data();
                
                // Create a table row element
                const row = document.createElement('tr');

                // Fetch user data function
               
                
                 // Format Firestore Timestamp
                 const timestamp = data.added_date;
                 const date = timestamp.toDate();
                 const humanReadableTime = date.toLocaleString('en-US', {
                     year: 'numeric', month: 'short', day: 'numeric',
                     hour: 'numeric', minute: 'numeric', second: 'numeric',
                     hour12: false
                 });
                // Fetch user data with await

                // Populate the row with data
                row.innerHTML = `
                    <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">${data.first_name} ${data.last_name}</td>
                    <td>${data.phone_number || 'N/A'}</td>
                    <td>${data.gender || 'N/A'}</td>
                    <td>${data.driver_verified || 'N/A'}</td>
                    <td>${humanReadableTime || 'N/A'}</td>
                    <td><!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Details
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">${data.first_name} ${data.last_name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            

                            <div class="card">
                            <div class="card-header">
                            <div class="card-title">
                            Load Owner Details
                            </div>
                          
                            </div>

                            <div class="card-body">
                            Phone#: <b>${data.phone_number}</b>
                            <br>
                            Gender#: <b>${data.gender}</b>
                            <br>
                            </div>
                            </div>


                            <br>
                             <div class="card">
                            <div class="card-header">
                            <div class="card-title">
                            Location Details
                            </div>
                          
                            </div>

                            <div class="card-body">
                             Province#: <b>${data.province}</b>
                            <br>
                            City/Town#: <b>${data.city_town}</b>
                            <br>
                            </div>

                             <br>
                             <div class="card-footer">
                             <button class="btn btn-danger">Reset Password</button>
                             </div>
                             </div>

   
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div> </td>
                   
                `;

                   
                // Append the row to the table body
                pendingOrdersContainer.appendChild(row);

              
        
               
            }
        });
    } catch (error) {
        console.error(`Error setting up real-time listener for pending orders: `, error);
    }
}

async function setupRealTimeTrucksListener(status, token) {
  try {
      // Convert `status` to a boolean if it's meant to represent a boolean value
      const statusBoolean = (status === '1' || status === true);

      // Define Firestore query
      let query = db.collection('trucks').where("status", '==', 'approved').where('isOnline', '==', statusBoolean);
      if (status === "all") {
          query = db.collection('trucks').where("status", '==', 'approved').where('status', '!=', 'yess');
      }

      // Attach real-time listener
      query.onSnapshot(async (snapshot) => {
          const pendingOrdersContainer = document.getElementById('trucksList');
          pendingOrdersContainer.innerHTML = ''; // Clear previous content

          if (snapshot.empty) {
              console.log("No trucks found.");
              return;
          }

          for (const doc of snapshot.docs) {
              try {
                  const data = doc.data();

                  // Fetch user data
                  const userData = await fetchUserData(data.userId);

                  // Fetch destinations sub-collection
                  const destinationsSnapshot = await db.collection('trucks')
                      .doc(doc.id)
                      .collection('destinations')
                      .get();

                  const destinations = [];
                  destinationsSnapshot.forEach((destDoc) => {
                      destinations.push(destDoc.data()); // Collect data from each destination document
                  });

                  // Format Firestore Timestamp
                  const humanReadableTime = formatTimestamp(data.added_date);

                  // Create a table row element
                  const row = document.createElement('tr');
                  row.innerHTML = `
                      <td>
                          <img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">
                          ${userData.first_name || "Unknown"} ${userData.last_name || "User"}
                      </td>
                      <td>${data.model || 'N/A'}</td>
                      <td>${data.licenseNumber}</td>
                      <td>${data.tonnage || 'N/A'}</td>
                      <td>${data.trailerType || 'N/A'}</td>
                      <td>${data.trailerType2 || 'N/A'}</td>
                      <td>${destinations.map(d => d.name).join(', ') || 'N/A'}</td>
                      <td>${userData.phone_number || 'N/A'}</td>
                      <td>
                          <input class="form-control" type="text" id="docIdInput" value="${doc.id || 'N/A'}" readonly>
                          <button class="btn btn-primary btn-sm" onclick="copyToClipboard()">Copy</button>
                      </td>
                  `;

                  // Append the row to the table
                  pendingOrdersContainer.appendChild(row);
              } catch (error) {
                  console.error("Error processing document:", error);
              }
          }
      });
  } catch (error) {
      console.error(`Error setting up real-time listener: `, error);
  }
}


// Helper function to fetch user data
async function fetchUserData(userId) {
  try {
      const userDoc = await db.collection('users').doc(userId).get();
      if (userDoc.exists) {
          return userDoc.data();
      }
      return { first_name: "Unknown", last_name: "User" };
  } catch (error) {
      console.error(`Error fetching user data for ID ${userId}:`, error);
      return { first_name: "Unknown", last_name: "User" };
  }
}

// Helper function to format Firestore Timestamp
function formatTimestamp(timestamp) {
  if (!timestamp) return 'N/A';
  const date = timestamp.toDate();
  return date.toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
      hour12: false
  });
}


//Real-time load owners load
async function setupRealTimePendingTransportListener(token) {
  try {
    let query = db.collection('users').where('user_type', '==', "Driver");

 
      query.onSnapshot(async snapshot => {
          const pendingOrdersContainer = document.getElementById('loadOwnersList');
          pendingOrdersContainer.innerHTML = ''; // Clear previous content

          for (const doc of snapshot.docs) {
              const data = doc.data();
              
              // Create a table row element
              const row = document.createElement('tr');

              // Fetch user data function
             
              
               // Format Firestore Timestamp
               const timestamp = data.added_date;
               const date = timestamp.toDate();
               const humanReadableTime = date.toLocaleString('en-US', {
                   year: 'numeric', month: 'short', day: 'numeric',
                   hour: 'numeric', minute: 'numeric', second: 'numeric',
                   hour12: false
               });
              // Fetch user data with await

              // Populate the row with data
              row.innerHTML = `
                  <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">${data.first_name} ${data.last_name}</td>
                  <td>${data.phone_number || 'N/A'}</td>
                  <td>${data.gender || 'N/A'}</td>
                  <td>${data.driver_verified || 'N/A'}</td>
                  <td>${humanReadableTime || 'N/A'}</td>
                  <td><!-- Button trigger modal -->
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Details
                  </button>
                  
                  <!-- Modal -->
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">${data.first_name} ${data.last_name}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          

                          <div class="card">
                          <div class="card-header">
                          <div class="card-title">
                          Load Owner Details
                          </div>
                        
                          </div>

                          <div class="card-body">
                          Phone#: <b>${data.phone_number}</b>
                          <br>
                          Gender#: <b>${data.gender}</b>
                          <br>
                          </div>
                          </div>


                          <br>
                           <div class="card">
                          <div class="card-header">
                          <div class="card-title">
                          Location Details
                          </div>
                        
                          </div>

                          <div class="card-body">
                          Province#: <b>${data.province}</b>
                          <br>
                          City/Town#: <b>${data.city_town}</b>
                          <br>
                          </div>

                           <br>
                           <div class="card-footer">
                           <button class="btn btn-danger">Reset Password</button>
                           </div>
                           </div>

 
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div> </td>
                 
              `;

                 
              // Append the row to the table body
              pendingOrdersContainer.appendChild(row);

            
      
             
          }
      });
  } catch (error) {
      console.error(`Error setting up real-time listener for pending orders: `, error);
  }
}

//Real-time load orders

async function setupRealTimeLoadPendingOrdersListener(order_status, token) {
    try {
      let query = db.collection('load_orders').where('order_status', '==', order_status);

      if (order_status == "all") {
        //For getting all the orders
         query = db.collection('load_orders').where('order_status', '!=', 'yess');
      }
        query.onSnapshot(async snapshot => {
            const pendingOrdersContainer = document.getElementById('pendingOrders');
            pendingOrdersContainer.innerHTML = ''; // Clear previous content
            let loopCount = 0;
            for (const doc of snapshot.docs) {
                const data = doc.data();
                loopCount++;
                
                // Create a table row element
                const row = document.createElement('tr');

                // Fetch user data function
                async function fetchUserData(doc) {
                    try {
                        const userDoc = await db.collection('users').doc(doc.cus_id).get();
                        let userName = "Unknown User"; // Default value in case user is not found

                        if (userDoc.exists) {
                            const userData = userDoc.data();
                            userName = userData || "No Name";
                        }

                        return userName;
                    } catch (error) {
                        console.log(error.toString());
                        return "Unknown User";
                    }
                }

                //Fetch load function
                 // Fetch Load data function
                 async function fetchLoadData(doc) {
                  try {
                      const loadDoc = await db.collection('loads').doc(data.load_id).get();
                      let loadName = "Unknown User"; // Default value in case user is not found

                      if (loadDoc.exists) {
                          const loadData = loadDoc.data();
                          loadName = loadData || "No Name";
                      }

                      return loadName;
                  } catch (error) {
                      console.log(error.toString());
                      return "Unknown Load";
                  }
              }
                
                 // Format Firestore Timestamp
                 const timestamp = data.added_date;
                 const date = timestamp.toDate();
                 const humanReadableTime = date.toLocaleString('en-US', {
                     year: 'numeric', month: 'short', day: 'numeric',
                     hour: 'numeric', minute: 'numeric', second: 'numeric',
                     hour12: false
                 });
                // Fetch user data with await
                let userData = await fetchUserData(data);
                let loadData = await fetchLoadData(data);

                


                

                // Populate the row with data
                row.innerHTML = `
                    <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">${userData.first_name} ${userData.last_name}</td>
                    <td>${loadData.load_name}</td>
                    <td>${data.order_status || 'N/A'}</td>
                    <td>${data.description || 'N/A'}</td>
                    <td>${humanReadableTime || 'N/A'}</td>
                    <td><!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Details
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                          <p>Load Name: <b>${loadData.load_name}</b></p>
                          <p>
                          Rate: <b> ${data.amount}  ${data.currency} </b>
                          </p>
                            <p>
                            Driver Name: <b> ${userData.first_name} ${userData.last_name} </b>
                            </p>
                            <p>
                            Phone Number: <b> ${userData.phone_number} </b>
                            </p>
                          
                            <p>
                            Pickup Location: <b> ${data.load_pickup} </b>
                            </p>
                            <p>
                            Drop off Location: <b> ${data.load_dropoff_destination} </b>
                            </p>

                            <br>
                            ${data.reject_reason != "" ? '<div class="card"> <div class="card-header text-danger"> Rejection reason </div> <div class="card-body"> '+data.reject_reason+' </div> </div>' : ""}
                            
                             
                            <hr>
                            `
                            if (data.order_status != "cancelled") {
                              
                            `
                            <div class="card">
                            <div class="card-header">
                            <div class="card-title">
                            Approve Order
                            </div>
                            <form method="post" action="">
                            <input hidden type="text" name="doc_id" value="${doc.id}" >
                            <input hidden type="text" name="_token" value="${token}" >
                            <input hidden type="text" name="load_id" value="${data.load_id}" >

                            <br>
                            <button name="approve_btn" class="btn btn-sm btn-primary" type="submit">Approve</button>

                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectOrderModal${loopCount}">Cancel Order</button>
                            </form>
                            </div>
                            </div>

   
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div> <!-- Rejection Modal -->
        <div class="modal fade" id="rejectOrderModal${loopCount}" tabindex="-1" aria-labelledby="rejectOrderLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rejectOrderLabel">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="post" action="">
                  <input type="hidden" name="doc_id" value="${doc.id}">
                  <input type="hidden" name="_token" value="${token}">
                  <input hidden type="text" name="load_id" value="${data.load_id}" >


                  <div class="mb-3">
                    <label for="rejectionReason" class="form-label">Reason for Cancellation:</label>
                    <textarea required class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required></textarea>
                  </div>
                  
                  <button name="cancel_btn" class="btn btn-danger" type="submit">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        `
                            }
                            `
    </td>
                   
                `;

                   
                // Append the row to the table body
                pendingOrdersContainer.appendChild(row);

                mapboxgl.accessToken = 'pk.eyJ1IjoiY29tZm9ydGNoYW1iZXNoaSIsImEiOiJjbHFpZDN4dXMxbmVsMmxvNGJlZDRmaGxjIn0.GVfGQ6EbVkDWGJHce0P8fA';
                var map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: [data.initial_longitude, data.initial_latitude], // Corrected order for Lusaka coordinates
                    zoom: 10
                });
        
                var marker = new mapboxgl.Marker()
                    .setLngLat([data.initial_longitude, data.initial_latitude]) // Corrected order for marker coordinates
                    .addTo(map);
            }
        });
    } catch (error) {
        console.error(`Error setting up real-time listener for pending orders: `, error);
    }
}


//Real-time applications

async function setupRealTimePendingApplicationsListener(order_status, token) {
    try {
      let query = db.collection('trucks').where('status', '==', order_status);

      if (order_status == "all") {
        //For getting all the orders
         query = db.collection('trucks').where('status', '!=', 'yess');
      }
        query.onSnapshot(async snapshot => {
            const pendingOrdersContainer = document.getElementById('pendingApplications');
            pendingOrdersContainer.innerHTML = ''; // Clear previous content
            let loopCount = 0;
            for (const doc of snapshot.docs) {
                const data = doc.data();
                loopCount++;
                                
                // Create a table row element
                const row = document.createElement('tr');

                // Fetch user data function
                async function fetchUserData(doc) {
                    try {
                        const userDoc = await db.collection('users').doc(doc.userId).get();
                        let userName = "Unknown User"; // Default value in case user is not found

                        if (userDoc.exists) {
                            const userData = userDoc.data();
                            userName = userData || "No Name";
                        }

                        return userName;
                    } catch (error) {
                        console.log(error.toString());
                        return "Unknown User";
                    }
                }
                
                 // Format Firestore Timestamp
                 const timestamp = data.added_date;
                 const date = timestamp.toDate();
                 const humanReadableTime = date.toLocaleString('en-US', {
                     year: 'numeric', month: 'short', day: 'numeric',
                     hour: 'numeric', minute: 'numeric', second: 'numeric',
                     hour12: false
                 });
                // Fetch user data with await
                let userData = await fetchUserData(data);
                //console.log("Hold: "+userData.first_name)
                let truck_images = await setupRealTimeTruckImagesListener(doc.id);

                //console.log(userData);

                // Populate the row with data
                row.innerHTML = `
                    <td>
                    <img class="rounded-circle me-2" width="30" height="30" src="assets/img/avatars/avatar1.jpeg">${userData.first_name} ${userData.last_name}</td>
                    <td>${data.status || 'N/A'}</td>
                    <td>${data.description || 'N/A'}</td>
                    <td>${humanReadableTime || 'N/A'}</td>
                    <td><!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Details
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">

                       
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">KYC Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                          
                            <br>
                            ${data.reject_reason && data.reject_reason !== "" && data.reject_reason !== "NE"  ? '<div class="card"> <div class="card-header text-danger"> Rejection reason </div> <div class="card-body"> '+data.reject_reason+' </div> </div>' : ""}
                          <br>   
                          <div class="card">
                          <div class="card-header">
                          <h4 class="card-title">Driver Details</h4>
                          </div>
                           
                          <div class="card-body">
                                <p>
                                Driver Name: <b>${userData.first_name} ${userData.last_name}</b>
                                </p>
                                 <p>
                                Country: <b>${userData.country}</b>
                                </p>
                                <p>
                                Phone#: <b>${userData.phone_number}</b>
                                </p>
                                <p>
                                NRC/Passport Number: <b>${data.idNumber}</b>
                                </p>
                               
                                
                                </div>
                            </div>

                            <hr>


                          <div class="card">
                          <div class="card-header">
                          <h4 class="card-title">Transporter/Company Details</h4>
                          </div>
                           
                          <div class="card-body">
                                <p>
                                Transporter/Company Name: <b>${data.transporterName}</b>
                                </p>
                                <p>
                                Transporter/Company Phone#: <b>${data.transporterPhone}</b>
                                </p>
                                                            
                                
                                </div>
                            </div>

                            <hr>
                                                      <div class="card">
                          <div class="card-header">
                          <h4 class="card-title">Driver NRC/Passport Documents</h4>
                          </div>
                           
                          <div class="card-body">
                                <p>
                                NRC/Passport front: <b><a target="blank" href="${data.idFrontUrl}"><img class="img img-thumbnail" style="width=100%; max-width="100%; height="250px"" src="${data.idFrontUrl}"></a></b>
                                </p>
                                <p>
                                NRC/Passport Back: <b><a target="blank" href="${data.idBackUrl}"><img class="img img-thumbnail" style="width=100%; max-width="100%; height="250px"" src="${data.idFrontUrl}"></a></b>
                                </p>
                                
                               
                                
                                </div>
                            </div>

                            <hr>
                            <div class="card">
                            <div class="card-header">
                            <div class="card-title">
                            Truck Details
                            <hr>
                            </div>


                            <p>
                              Model: <b>${data.model}</b>
                              <br>
                              Trailer Type 1: <b>${data.trailerType}</b>
                              <br>
                              Trailer Type 2: <b>${data.trailerType2}</b>

                            </p>
                       

                                             
                            <div class="card">
                             <div class="card-header">
                               <h4 class="card-title"> Truck Reg Number:<b> ${data.licenseNumber} </b>:</h4>
                             </div>

                             <div class="card-body">
                             
                               <b><a target="blank" href="${data.licenseUrl}">
                            <img class="img img-thumbnail" style="width=100%; max-width="100%; height="250px"" src="${data.licenseUrl}"></a></b>
                               
                             </div>
                             </div>

                             <br>


                              <div class="card">
                             <div class="card-header">
                               <h4 class="card-title"> Side View:</h4>
                             </div>

                             <div class="card-body">
                             
                               <b><a target="blank" href="${data.sideViewUrl}">
                            <img class="img img-thumbnail" style="width=100%; max-width="100%; height="250px"" src="${data.sideViewUrl}"></a></b>
                               
                             </div>
                             </div>

                             <br>

                             <div class="card">
                             <div class="card-header">
                               <h4 class="card-title"> Trailer Reg Number : <b>${data.trailerNumber}</b></h4>
                             </div>

                             <div class="card-body">
                             
                               <b><a target="blank" href="${data.trailerUrl}">
                            <img class="img img-thumbnail" style="width=100%; max-width="100%; height="250px"" src="${data.trailerUrl}"></a></b>
                               
                             </div>
                             </div>

                             <br>

                             

                            </form>
                            </div>
                            </div>
                            <hr>
                            <div class="text-center">
                            <form method="post" action="">
                            <input hidden type="text" name="doc_id" value="${doc.id}" >
                            <input hidden type="text" name="user_id" value="${data.userId}" >
                            <input hidden type="text" name="fcm_token" value="${userData.fcm_token}" >
<input hidden type="text" name="phone_number" value="${userData.phone_number}" >

                            <input type="hidden" name="_token" value="${token}">
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectOrderModal${loopCount}">Reject</button>
 . <button name="approve_btn" class="btn btn-sm btn-primary" type="submit">Approve</button> . 
                                            

                            </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                          </div>
                        </div>
                      </div>
                    </div>  <!-- Rejection Modal -->
                    <div class="modal fade" id="rejectOrderModal${loopCount}" tabindex="-1" aria-labelledby="rejectOrderLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectOrderLabel">Cancel Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="post" action="">
                              <input type="hidden" name="doc_id" value="${doc.id}">
                              <input type="hidden" name="_token" value="${token}">
                              <input hidden type="text" name="fcm_token" value="${userData.fcm_token}" >
<input hidden type="text" name="phone_number" value="${userData.phone_number}" >



                              <div class="mb-3">
                                <label for="rejectionReason" class="form-label">Reason for rejection:</label>
                                <textarea required class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required></textarea>
                              </div>
                              
                              <button name="reject_btn" class="btn btn-danger" type="submit">Submit</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                   </td>
                   
                `;

                   
                // Append the row to the table body
                pendingOrdersContainer.appendChild(row);

               
            }
        });
    } catch (error) {
        console.error(`Error setting up real-time listener for pending orders: `, error);
    }
}

  


async function setupRealTimeTruckImagesListener(truck_id) {
    try {
        let query = db.collection('item_images').where('truck_id', '==', truck_id);
        
        query.onSnapshot(async snapshot => {
            // Clear previous content in the #truck_photos container
            const truckPhotosContainer = document.getElementById('truck_photos');
            truckPhotosContainer.innerHTML = ''; // Clear the container

            // Create a new div for the row
            const rowDiv = document.createElement('div');
            rowDiv.className = 'row'; // Apply the Bootstrap row class

            // Iterate over each document in the snapshot
            for (const doc of snapshot.docs) {
                const data = doc.data();
                
                // Create a new <a> element
                const divMain = document.createElement('div');
                divMain.style = "margin-top: 10px;";

                const link = document.createElement('a');
                link.href = data.image_url; // Replace with the desired URL or add more logic if needed
                link.className = 'col col-lg-6'; // Use Bootstrap column classes
                link.style.textDecoration = 'none'; // Remove default link decoration if needed

                // Create the image element
                const imageElement = document.createElement('img');
                imageElement.className = 'img-thumbnail img-fluid'; // Bootstrap classes for responsive images
                imageElement.src = data.image_url;
                imageElement.alt = 'Truck Image'; // Add alt text for accessibility
                imageElement.style.width = '100%'; // Ensure the image covers its container
                imageElement.style.maxWidth = '100%';
                
                link.appendChild(divMain);
                // Append the image to the link
                link.appendChild(imageElement);

                // Append the link to the row div
                rowDiv.appendChild(link);
            }

            // Append the row div to the truck_photos container
            truckPhotosContainer.appendChild(rowDiv);
        });
    } catch (error) {
        console.error(`Error setting up real-time listener for truck images: `, error);
    }
}

  