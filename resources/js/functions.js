import {refetchEventsEvent} from "./app.js";

export function handleDateClick(info) {

    $("#event_entry_modal").modal("show");
    const updateEventButton = document.getElementById("update_event_button");
    updateEventButton.disabled = true;

       // Clear form fields and set the selected date as the default upload date
       clearFormFieldsIfNotNull(info.dateStr);
}

export async function saveEvent() {
    // Disable the button to prevent multiple submissions
    const saveEventButton = document.getElementById("save_event_button");
    saveEventButton.disabled = true;
    const updateEventButton = document.getElementById("update_event_button");
    

    const duration = await whichControl();

    if (!duration) {
        
        return;
    }

    const typeOfControl = document.querySelector('input[name="type_of_control"]:checked').value;
    const castName = document.getElementById("cast_name").value;
    const mainCastName = document.getElementById("maincast_name").value;
    let isTranslated = document.querySelector('input[name="is_translated"]:checked')?.value || "not_applicable";
    const channelName = document.getElementById("channel_name").value;
    const uploadDate = document.getElementById("upload_date").value;
    const playDate = document.getElementById("play_date").value;
    const startTime = document.getElementById("start_time").value;
    const endTime = document.getElementById("end_time").value;
    const endDate = calculateEndDate(playDate, duration);

    try {
        const response = await fetch("/save-event", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({
                cast_name: castName,
                main_cast_name: mainCastName,
                is_translated: isTranslated,
                type_of_control: typeOfControl,
                channel_name: channelName,
                duration: duration,
                upload_date: uploadDate,
                play_date: playDate,
                start_time: startTime,
                end_time: endTime,
                end_date: endDate,
            }),
        });
        handleSaveResponse(response);
    } catch (error) {
        handleSaveError(error);
    } finally {
        // Re-enable the button after the request is complete
        saveEventButton.disabled = false;
        updateEventButton.disabled = false;
        $("#event_entry_modal").modal("hide");
    }
}

function handleUpdateResponse(response) {
    if (response.ok) {
        console.log("Event updated successfully");
        location.reload();
    } else {
        console.error("Failed to update event");
        
    }
}

export function handleUpdateError(error) {
    console.error("Error occurred during update request:", error);
    clearFormFields();
}

function handleSaveResponse(response) {
    response.json().then(data => {
        if (response.ok) {
            if (data.success) {
                // console.log("Event saved successfully");
                //location.reload();
                clearFormFields();
                // console.log("Calling refetch");
                window.dispatchEvent(refetchEventsEvent);
            } else {
                alert(data.message || 'Failed to save event.');
            }
        } else {
            console.error("Failed to save event");
            alert(data.message || 'Oops! The above cast already exists for the same channel.');
            clearFormFields();
            console.log("Calling refetch");
            window.dispatchEvent(refetchEventsEvent);
        }
    }).catch(error => {
        console.error("Error parsing JSON:", error);
       // alert('An unexpected error occurred.');
    });
}

function handleSaveError(error) {
    console.error("Error occurred during fetch request:", error);
    clearFormFields();
}

async function whichControl() {
    const control = document.querySelector('input[name="type_of_control"]:checked').value;
    if (control === "Movie") {
        return await checkDurationMovies();
    } else if (control === "Music") {
        return await checkDurationMusic();
    } else {
        alert("Please select the type of control for the programme");
        return false;
    }
}

async function checkDurationMovies() {
    const response = await fetch("/get-Movieduration");
    const data = await response.json();
    const duration = parseInt(data.movie_duration, 10);

    return duration;
}

async function checkDurationMusic() {
    const response = await fetch("/get-Musicduration");
    const data = await response.json();
    const duration = parseInt(data.music_duration, 10);

    return duration;
}

function calculateEndDate(startDateStr, duration) {
    const startDate = new Date(startDateStr);
    startDate.setDate(startDate.getDate() + duration);
    return startDate.toISOString().split('T')[0];
}


export function clearFormFields() {
    document.getElementById("cast_name").value = "";
    document.getElementById("maincast_name").value = "";
    const isTranslatedChecked = document.querySelector('input[name="is_translated"]:checked');
    if (isTranslatedChecked) {
        isTranslatedChecked.checked = false;
    }
    document.querySelector('input[name="type_of_control"]:checked').checked = false;
    document.getElementById("channel_name").value = "";
    document.getElementById("upload_date").value = "";
    document.getElementById("play_date").value = "";
    document.getElementById("start_time").value = "";
    document.getElementById("end_time").value = "";
    // Clear other form fields as needed
}


function clearFormFieldsIfNotNull(clickedDate) {
    const fieldsToClear = [
        "cast_name",
        "maincast_name",
        "channel_name",
        "duration",
        "play_date",
        "start_time",
        "end_time"
    ];

    fieldsToClear.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value) {
            field.value = "";
        }
    });

    const isTranslatedChecked = document.querySelector('input[name="is_translated"]:checked');
    if (isTranslatedChecked) {
        isTranslatedChecked.checked = false;
    }

    const typeOfControlChecked = document.querySelector('input[name="type_of_control"]:checked');
    if (typeOfControlChecked) {
        typeOfControlChecked.checked = false;
    }

    // Set the selected date as the default upload date
    const uploadDateInput = document.getElementById("upload_date");
    if (uploadDateInput) {
        uploadDateInput.value = clickedDate;
    }

    const saveEventButton = document.getElementById("save_event_button");
    saveEventButton.enabled = true;
}

export function handleCastNameClick(event) {
    const castName = event.target.textContent;
    const saveEventButton = document.getElementById("save_event_button");
    saveEventButton.disabled = true;

    // Fetch cast data based on the cast name
    fetch(`/get-cast/${castName}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(async (data) => {
            // Populate modal with cast data
            $("#cast_name").val(data.cast_name);
            $("#maincast_name").val(data.main_cast_name);

            // Set is_translated radio button, handle null or not_applicable by not checking any button
            if (data.is_translated === "yes" || data.is_translated === "no") {
                $(`input[name="is_translated"][value="${data.is_translated}"]`).prop("checked", true);
            } else {
                $('input[name="is_translated"]').prop("checked", false);
            }

            $(`input[name="type_of_control"][value="${data.type_of_control}"]`).prop("checked", true);
            $("#channel_name").val(data.channel_name);
            $("#upload_date").val(data.upload_date);
            $("#play_date").val(data.play_date);
            $("#start_time").val(data.start_time);
            $("#end_time").val(data.end_time);

            // Determine duration based on the type of control
            let duration;
            if (data.type_of_control === "Movie") {
                duration = await checkDurationMovies();
            } else {
                duration = await checkDurationMusic();
            } 

             // Calculate end date
             const endDate = calculateEndDate(data.play_date, duration);
             $("#end_date").val(endDate);
 
            // Handle the update event button click
            $("#update_event_button").off('click').on('click', function () {
                const updatedCastName = $("#cast_name").val();
                const updatedMainCastName = $("#maincast_name").val();
                const updatedIsTranslated = $("input[name='is_translated']:checked").val() || "not_applicable";
                const updatedTypeOfControl = $("input[name='type_of_control']:checked").val();
                const updatedChannelName = $("#channel_name").val();
                const updatedUploadDate = $("#upload_date").val();
                const updatedPlayDate = $("#play_date").val();
                const updatedDuration =duration;
                const updatedStartTime = $("#start_time").val();
                const updatedEndTime = $("#end_time").val();

                fetch(`/update-event/${data.cast_name}`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({
                        cast_name: updatedCastName,
                        main_cast_name: updatedMainCastName,
                        is_translated: updatedIsTranslated,
                        type_of_control: updatedTypeOfControl,
                        channel_name: updatedChannelName,
                        duration: updatedDuration,
                        upload_date: updatedUploadDate,
                        play_date: updatedPlayDate,
                        start_time: updatedStartTime,
                        end_time: updatedEndTime,
                    }),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to update event');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log("Event updated successfully");
                            location.reload(); // Reload the page or update UI as needed
                        } else {
                            console.error("Failed to update event");
                        }
                    })
                    .catch(error => {
                        console.error("Error occurred during update request:", error.message);
                        location.reload(); // Refresh the page on error
                    });
                  
            });
        })
        .catch((error) => {
            console.error("Error fetching cast data:", error.message);
            
        })
        .finally(() => {
            saveEventButton.disabled = true;
        });

    // Show the modal
    $("#event_entry_modal").modal("show");
}
