$(document).ready(function () {

    // Initial load
    fetchStudents("");

    // Create / Update submit
    $("#studentForm").on("submit", function (e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        let id = $("#id").val();
        let action = id ? "update" : "create";

        $.ajax({
            url: "ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                action: action,
                id: id,
                student_id: $("#student_id").val().toUpperCase(),
                name: $("#name").val(),
                email: $("#email").val(),
                course: $("#course").val(),
                semester: $("#semester").val()
            },
            success: function (res) {
                $("#msg")
                    .text(res.message)
                    .css("color", res.status === "success" ? "green" : "red");

                if (res.status === "success") {
                    resetForm();
                    fetchStudents($("#searchBox").val());
                }
            },
            error: function () {
                $("#msg").text("Something went wrong.").css("color", "red");
            }
        });
    });

    // Reset button
    $("#resetBtn").on("click", function () {
        resetForm();
        $("#msg").text("");
    });

    // Edit record
    $(document).on("click", ".btn-edit", function () {
        let id = $(this).data("id");

        $.ajax({
            url: "ajax.php",
            type: "POST",
            dataType: "json",
            data: {action: "get_single", id: id},
            success: function (res) {
                if (res.status === "success") {
                    let s = res.data;
                    $("#id").val(s.id);
                    $("#student_id").val(s.student_id);
                    $("#name").val(s.name);
                    $("#email").val(s.email);
                    $("#course").val(s.course);
                    $("#semester").val(s.semester);

                    $("#form-title").text("Update Student");
                    $("#saveBtn").text("Update");
                    $("#msg").text("");

                    window.scrollTo({top: 0, behavior: "smooth"});
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // Delete record
    $(document).on("click", ".btn-delete", function () {
        if (!confirm("Are you sure you want to delete this record?")) return;

        let id = $(this).data("id");
        $.ajax({
            url: "ajax.php",
            type: "POST",
            dataType: "json",
            data: {action: "delete", id: id},
            success: function (res) {
                alert(res.message);
                if (res.status === "success") {
                    fetchStudents($("#searchBox").val());
                }
            }
        });
    });

    // Search as you type
    $("#searchBox").on("keyup", function () {
        let q = $(this).val();
        fetchStudents(q);
    });
});

// Validate form on client side
function validateForm() {
    let sid = $("#student_id").val().trim().toUpperCase();
    let name = $("#name").val().trim();
    let email = $("#email").val().trim();
    let course = $("#course").val().trim();
    let semester = $("#semester").val().trim();

    if (!sid || !name || !email || !course || !semester) {
        $("#msg").text("All fields are required.").css("color", "red");
        return false;
    }

    // Student ID: 2 digits + 4 caps letters + 2 digits
    let sidPattern = /^[0-9]{2}[A-Z]{4}[0-9]{2}$/;
    if (!sidPattern.test(sid)) {
        $("#msg").text("Student ID must be like 25ABCD01.").css("color", "red");
        return false;
    }

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        $("#msg").text("Please enter a valid email.").css("color", "red");
        return false;
    }

    return true;
}

// Reset form to "Add" mode
function resetForm() {
    $("#studentForm")[0].reset();
    $("#id").val("");
    $("#form-title").text("Add New Student");
    $("#saveBtn").text("Save");
}

// Fetch students (optionally with search)
function fetchStudents(search) {
    $.ajax({
        url: "ajax.php",
        type: "POST",
        dataType: "json",
        data: {
            action: "read",
            search: search
        },
        success: function (res) {
            if (res.status === "success") {
                let rows = "";
                let data = res.data;
                for (let i = 0; i < data.length; i++) {
                    let s = data[i];
                    rows += `<tr>
                        <td>${i + 1}</td>
                        <td>${s.student_id}</td>
                        <td>${s.name}</td>
                        <td>${s.email}</td>
                        <td>${s.course}</td>
                        <td>${s.semester}</td>
                        <td>
                            <button class="action-btn action-edit btn-edit" data-id="${s.id}">Edit</button>
                            <button class="action-btn action-delete btn-delete" data-id="${s.id}">Delete</button>
                        </td>
                    </tr>`;
                }
                $("#studentsTable tbody").html(rows);
                $("#recordCount").text(data.length ? data.length + " records" : "No records");
            }
        }
    });
}
