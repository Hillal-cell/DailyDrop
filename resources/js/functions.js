// import { calendar } from './app.js'; // Adjust the path as necessary

export function handleDateClick(info) {
    $("#event_entry_modal").modal("show");

    // Set the selected date as the default upload date
    const uploadDateInput = document.getElementById("upload_date");
    uploadDateInput.value = info.dateStr;
}

export function saveEvent() {
    // Disable the button to prevent multiple submissions
    const saveEventButton = document.getElementById("save_event_button");
    saveEventButton.disabled = true;

    const castName = document.getElementById("cast_name").value;
    const mainCastName = document.getElementById("maincast_name").value;
    const isTranslated = document.querySelector(
        'input[name="is_translated"]:checked'
    ).value;
    const channelName = document.getElementById("channel_name").value;
    const uploadDate = document.getElementById("upload_date").value;
    const playDate = document.getElementById("play_date").value;

    fetch("/save-event", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            cast_name: castName,
            main_cast_name: mainCastName,
            is_translated: isTranslated,
            channel_name: channelName,
            upload_date: uploadDate,
            play_date: playDate,
        }),
    })
        .then(handleSaveResponse)
        .catch(handleSaveError)
        .finally(() => {
            // Re-enable the button after the request is complete
            saveEventButton.disabled = false;
        });

    $("#event_entry_modal").modal("hide");
}

export function handleCastNameClick(event) {
    const castName = event.target.textContent;

    // Fetch cast data based on the cast name
    fetch(`/get-cast/${castName}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            // Populate modal with cast data
            $("#cast_name").val(data.cast_name);
            $("#maincast_name").val(data.main_cast_name);
            $(
                `input[name="is_translated"][value="${data.is_translated}"]`
            ).prop("checked", true);
            $("#channel_name").val(data.channel_name);
            $("#upload_date").val(data.upload_date);
            $("#play_date").val(data.play_date);

            console.log("Data fetched successfully:", data);

            // Now, when the data is loaded into the modal, you can provide an option to update it
            $("#update_event_button").click(function () {
                // Get the updated values from the modal
                const updatedCastName = $("#cast_name").val();
                const updatedMainCastName = $("#maincast_name").val();
                const updatedIsTranslated = $(
                    "input[name='is_translated']:checked"
                ).val();
                const updatedChannelName = $("#channel_name").val();
                const updatedUploadDate = $("#upload_date").val();
                const updatedPlayDate = $("#play_date").val();

                // Send the updated data to the server
                fetch(`/update-event/${data.updatedCastName}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        cast_name: updatedCastName,
                        main_cast_name: updatedMainCastName,
                        is_translated: updatedIsTranslated,
                        channel_name: updatedChannelName,
                        upload_date: updatedUploadDate,
                        play_date: updatedPlayDate,
                    }),
                })
                    .then(handleUpdateResponse)
                    .catch(handleUpdateError);
            });
        })
        .catch((error) => {
            console.error("Error fetching cast data:", error);
        });

    // Show the modal
    $("#event_entry_modal").modal("show");
}

export function handleUpdateResponse(response) {
    if (response.ok) {
        console.log("Event updated successfully");
        // You can perform any necessary actions upon successful update
    } else {
        console.error("Failed to update event");
    }
}

export function handleUpdateError(error) {
    console.error("Error occurred during update request:", error);
}

export function handleSaveResponse(response) {
    if (response.ok) {
        console.log("Event saved successfully");
        location.reload();
    } else {
        console.error("Failed to save event");
    }
}

export function handleSaveError(error) {
    console.error("Error occurred during fetch request:", error);
}
