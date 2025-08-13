/**
 * Role Management Functions
 * Handles role assignment, removal, and synchronization across the application
 */

class RoleManager {
    constructor() {
        this.currentUserRoles = [];
        this.allRoles = [];
        this.userId = null;
    }

    /**
     * Initialize role manager with user data
     */
    init(userId, userRoles = [], allRoles = []) {
        this.userId = userId;
        this.currentUserRoles = userRoles.map(role => role.id || role);
        this.allRoles = allRoles;
        console.log('RoleManager initialized:', {
            userId: this.userId,
            currentRoles: this.currentUserRoles,
            allRoles: this.allRoles
        });
    }

    /**
     * Check if a role is assigned to the user
     */
    isRoleAssigned(roleId) {
        return this.currentUserRoles.includes(parseInt(roleId));
    }

    /**
     * Get current user roles
     */
    getCurrentRoles() {
        return [...this.currentUserRoles];
    }

    /**
     * Update role assignment (add or remove)
     */
    updateRole(roleId, isChecked) {
        const roleIdInt = parseInt(roleId);
        
        if (isChecked && !this.isRoleAssigned(roleIdInt)) {
            // Add role
            this.currentUserRoles.push(roleIdInt);
            console.log(`Role ${roleId} added to user ${this.userId}`);
        } else if (!isChecked && this.isRoleAssigned(roleIdInt)) {
            // Remove role
            this.currentUserRoles = this.currentUserRoles.filter(id => id !== roleIdInt);
            console.log(`Role ${roleId} removed from user ${this.userId}`);
        }
        
        console.log('Updated roles:', this.currentUserRoles);
        return this.currentUserRoles;
    }

    /**
     * Sync roles with server
     */
    async syncRoles(endpoint, csrfToken) {
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    roles: this.currentUserRoles
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Role sync result:', result);
            
            if (result.success) {
                this.showNotification(result.message || 'Roles updated successfully', 'success');
                return true;
            } else {
                this.showNotification(result.message || 'Failed to update roles', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error syncing roles:', error);
            this.showNotification('Error updating roles. Please try again.', 'error');
            return false;
        }
    }

    /**
     * Create role checkboxes HTML
     */
    createRoleCheckboxes(containerId, onChangeCallback = null) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`Container ${containerId} not found`);
            return;
        }

        const checkboxesHtml = this.allRoles.map(role => {
            const isChecked = this.isRoleAssigned(role.id);
            return `
                <label class="cursor-pointer flex items-center">
                    <input type="checkbox" 
                           name="roles[]" 
                           value="${role.id}" 
                           class="hidden role-checkbox" 
                           ${isChecked ? 'checked' : ''}
                           data-role-id="${role.id}">
                    <span class="px-3 py-1 rounded-full border ${isChecked ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-400 text-gray-700'} mr-2">
                        ${role.display_name || role.name}
                    </span>
                </label>
            `;
        }).join('');

        container.innerHTML = checkboxesHtml;

        // Add event listeners
        container.querySelectorAll('.role-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const roleId = e.target.value;
                const isChecked = e.target.checked;
                
                // Update internal state
                this.updateRole(roleId, isChecked);
                
                // Update visual state
                this.updateCheckboxVisual(e.target, isChecked);
                
                // Call callback if provided
                if (onChangeCallback && typeof onChangeCallback === 'function') {
                    onChangeCallback(roleId, isChecked, this.getCurrentRoles());
                }
            });
        });
    }

    /**
     * Update checkbox visual state
     */
    updateCheckboxVisual(checkbox, isChecked) {
        const span = checkbox.nextElementSibling;
        if (isChecked) {
            span.classList.remove('border-gray-400', 'text-gray-700');
            span.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
        } else {
            span.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
            span.classList.add('border-gray-400', 'text-gray-700');
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        if (typeof Toastify !== 'undefined') {
            const colors = {
                success: '#10B981',
                error: '#EF4444',
                warning: '#F59E0B',
                info: '#3B82F6'
            };

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: colors[type] || colors.info,
                stopOnFocus: true
            }).showToast();
        } else {
            // Fallback to alert if Toastify is not available
            alert(message);
        }
    }

    /**
     * Handle form submission for role assignment
     */
    handleFormSubmission(formElement, endpoint, csrfToken) {
        formElement.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get all checked roles
            const checkedRoles = formElement.querySelectorAll('input[name="roles[]"]:checked');
            const roleIds = Array.from(checkedRoles).map(cb => parseInt(cb.value));
            
            // Update internal state
            this.currentUserRoles = roleIds;
            
            // Sync with server
            const success = await this.syncRoles(endpoint, csrfToken);
            
            if (success) {
                // Close modal if it exists
                const modal = document.getElementById('assignRolesModal');
                if (modal) {
                    modal.classList.add('hidden');
                }
                
                // Reload page or update UI as needed
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        });
    }
}

// Global instance
window.roleManager = new RoleManager();

// Utility functions for backward compatibility
window.updateUserRoles = function(userId, userRoles, allRoles) {
    window.roleManager.init(userId, userRoles, allRoles);
};

window.openAssignRolesModal = function(userId) {
    // This will be overridden by specific implementations
    console.log('openAssignRolesModal called for user:', userId);
};

window.closeAssignRolesModal = function() {
    const modal = document.getElementById('assignRolesModal');
    if (modal) {
        modal.classList.add('hidden');
    }
};
