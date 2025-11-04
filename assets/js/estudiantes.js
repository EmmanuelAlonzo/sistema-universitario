/**
 * JAVASCRIPT: Gestión de Estudiantes con AJAX
 * Operaciones CRUD completas
 */

// Variables globales
let currentStudentId = null;

/**
 * LOAD STUDENTS - Cargar todos los estudiantes
 */
function loadStudents() {
    fetch('../controllers/EstudianteController.php?action=read')
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            displayStudents(data.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

/**
 * DISPLAY STUDENTS - Mostrar estudiantes en tabla
 */
function displayStudents(estudiantes) {
    const tbody = document.getElementById('studentsTableBody');
    if(!tbody) return;

    if(estudiantes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">No hay estudiantes registrados</td></tr>';
        return;
    }

    tbody.innerHTML = estudiantes.map(est => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${est.student_id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${est.nombre}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${est.email}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${est.carrera}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${est.semestre}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${est.estado === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${est.estado === 'active' ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editStudent(${est.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                <button onclick="deleteStudent(${est.id})" class="text-red-600 hover:text-red-900">Eliminar</button>
            </td>
        </tr>
    `).join('');
}

/**
 * CREATE STUDENT - Crear nuevo estudiante
 */
function createStudent() {
    const form = document.getElementById('studentForm');
    const formData = new FormData(form);
    formData.append('action', 'create');

    fetch('../controllers/EstudianteController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification(data.message, 'success');
            closeStudentModal();
            loadStudents();
            form.reset();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al crear estudiante', 'error');
    });
}

/**
 * EDIT STUDENT - Editar estudiante
 */
function editStudent(id) {
    currentStudentId = id;
    
    // Obtener datos del estudiante
    fetch(`../controllers/EstudianteController.php?action=read_one&id=${id}`)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const student = data.data;
            
            // Llenar el formulario con los datos
            document.getElementById('edit_nombre').value = student.nombre;
            document.getElementById('edit_email').value = student.email;
            document.getElementById('edit_telefono').value = student.telefono || '';
            document.getElementById('edit_carrera').value = student.carrera;
            document.getElementById('edit_semestre').value = student.semestre;
            document.getElementById('edit_estado').value = student.estado;
            
            // Mostrar modal de edición
            document.getElementById('editStudentModal').classList.remove('hidden');
        }
    })
    .catch(error => console.error('Error:', error));
}

/**
 * UPDATE STUDENT - Actualizar estudiante
 */
function updateStudent() {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', currentStudentId);
    formData.append('nombre', document.getElementById('edit_nombre').value);
    formData.append('email', document.getElementById('edit_email').value);
    formData.append('telefono', document.getElementById('edit_telefono').value);
    formData.append('carrera', document.getElementById('edit_carrera').value);
    formData.append('semestre', document.getElementById('edit_semestre').value);
    formData.append('estado', document.getElementById('edit_estado').value);

    fetch('../controllers/EstudianteController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification(data.message, 'success');
            closeEditModal();
            loadStudents();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al actualizar estudiante', 'error');
    });
}

/**
 * DELETE STUDENT - Eliminar estudiante
 */
function deleteStudent(id) {
    if(!confirm('¿Está seguro que desea eliminar este estudiante?')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);

    fetch('../controllers/EstudianteController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification(data.message, 'success');
            loadStudents();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al eliminar estudiante', 'error');
    });
}

/**
 * SEARCH STUDENTS - Buscar estudiantes
 */
function searchStudents() {
    const keyword = document.getElementById('searchInput').value;
    
    if(!keyword.trim()) {
        loadStudents();
        return;
    }

    fetch(`../controllers/EstudianteController.php?action=search&keyword=${encodeURIComponent(keyword)}`)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            displayStudents(data.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

/**
 * MODAL FUNCTIONS
 */
function showStudentModal() {
    document.getElementById('studentModal').classList.remove('hidden');
}

function closeStudentModal() {
    document.getElementById('studentModal').classList.add('hidden');
    document.getElementById('studentForm').reset();
}

function closeEditModal() {
    document.getElementById('editStudentModal').classList.add('hidden');
    currentStudentId = null;
}

/**
 * SHOW NOTIFICATION - Mostrar notificación
 */
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white z-50`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Cargar estudiantes al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('studentsTableBody')) {
        loadStudents();
    }
});
