class ResearchProjectsList extends HTMLElement {
    constructor() {
        super();
        this.internals = this.attachInternals();
    }

    $(q) {
        return this.internals.shadowRoot.querySelector(q);
    }

    async connectedCallback() {
        await new Promise(resolve => {
            const ci = setInterval(() => {
                if (this.internals.shadowRoot) {
                    clearInterval(ci);
                    resolve();
                }
            }, 16);
        });

        this.renderDepartmentOptions();
        this.registerEventListeners();
        this.moveListItems();
    }

    /**
     * Move the slotted ul form light dom into the shadow dom to enable styling of list items.
     */
    moveListItems() {
        const slot = this.$('slot[name="list"]');
        const nodes = slot.assignedNodes();

        nodes.forEach(node => {
            if (node.tagName === 'UL') {
                slot.replaceWith(node);
            }
        });
    }

    clearFilter () {
        this.$('#temp-filter').textContent = '';
    }

    filterBy (attribute, value, match = '') {
        this.$('#temp-filter').textContent = `
            li:not([data-${attribute}${match}="${value}"]) {
                display: none;
            }
        `;
    }

    /**
     * 
     * @param {InputEvent} event 
     */
    handleSelectDepartment(event) {
        const departmentId = event.target.value;

        // Show all items if the department id is 1 (main site)
        if (event.target.value === "1") {
            this.clearFilter();
            return;
        }

        this.filterBy('department-id', departmentId);
    }

    /**
     * 
     * @param {InputEvent} event 
     */
    handleSelectStatus(event) {
        const status = event.target.value;

        // Show all items if the department id is 1 (main site)
        if (event.target.value === "all") {
            this.clearFilter();
            return;
        }

        this.filterBy('status', status);
    }

    /**
     * 
     * @param {InputEvent} event 
     */
    handleSearchFilter(event) {
        const query = event.target.value;

        if (event.target.value === "") {
            this.clearFilter();
            return;
        }

        this.filterBy('text', query, '*');
    }

    registerEventListeners() {
        const $selectDepartment = this.$('#department');
        const $selectStatus = this.$('#status');
        const $inputSearch = this.$('#search');

        if ($selectDepartment) {
            $selectDepartment.addEventListener('input', event => this.handleSelectDepartment(event));
        }

        if ($selectStatus) {
            $selectStatus.addEventListener('input', event => this.handleSelectStatus(event));
        }

        if ($inputSearch) {
            $inputSearch.addEventListener('input', event => this.handleSearchFilter(event));
        }
    }

    async renderDepartmentOptions() {
        const response = await fetch('/wp-json/wp-kau-rsp/v1/research-projects');
        const { departments } = await response.json();

        const $selectDepartment = this.$('#department');

        if ($selectDepartment) {
            for (const department of departments) {
                const option = document.createElement('option');

                option.value = department.id;
                option.textContent = department.name;

                $selectDepartment.appendChild(option);
            }
        }
    }
}

customElements.define('research-projects-list', ResearchProjectsList);
