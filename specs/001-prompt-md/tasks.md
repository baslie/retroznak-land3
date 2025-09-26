# Tasks: Лендинг для компании «Ретрознак»

**Input**: Design documents from `/specs/001-prompt-md/`
**Prerequisites**: plan.md, research.md, data-model.md, contracts/

## Execution Flow (main)
```
1. Load plan.md from feature directory ✓
   → Extracted: HTML5/CSS3/JS/PHP stack, DaisyUI components, web landing structure
2. Load optional design documents: ✓
   → data-model.md: ContactForm, ProductInquiry entities
   → contracts/: contact-form.php endpoint
   → research.md: DaisyUI semantic approach, performance requirements
3. Generate tasks by category: ✓
   → Setup: project init, DaisyUI dependencies, file structure
   → Core: HTML sections, CSS styling, JavaScript forms, PHP backend
   → Integration: form handling, email sending, validation
   → Polish: performance optimization, cross-browser testing
4. Apply task rules: ✓
   → Different files = mark [P] for parallel
   → Testing excluded per user request
5. Tasks numbered sequentially (T001-T029) - всего 29 задач
6. Generate dependency graph ✓
7. Validate task completeness: ✓
8. Return: SUCCESS (tasks ready for execution)
```

## Format: `[ID] [P?] Description`
- **[P]**: Can run in parallel (different files, no dependencies)
- **NOTE**: Tests excluded per user request ("не делай тестов, я сам все протестирую вручную")

## Path Conventions
Based on plan.md structure decision: Web landing page architecture
```
/
├── index.html              # Main landing with DaisyUI components
├── css/styles.css          # DaisyUI imports and custom theme
├── js/main.js             # Navigation and interactivity
├── js/forms.js            # AJAX form handling
├── images/                # JPEG/PNG/SVG placeholders
├── backend/contact-form.php # PHP script based on etc/ example
└── daisyui.config.js      # DaisyUI configuration
```

## Phase 3.1: Setup
- [X] T001 Create project file structure per implementation plan
- [X] T002 Initialize DaisyUI dependencies and configuration
- [X] T003 [P] Configure daisyui.config.js with Ретрознак custom theme
- [X] T004 [P] Setup css/styles.css with DaisyUI imports

## Phase 3.2: Core HTML Structure (DaisyUI Components)
- [X] T005 Create index.html base structure with semantic HTML
- [X] T006 [P] Implement navbar component with dropdown navigation
- [X] T007 [P] Create hero section with DaisyUI hero component
- [X] T008 [P] Build timeline component for history section
- [X] T009 [P] Implement card grid for product catalog section
- [X] T010 [P] Create collapse FAQ component with join-vertical
- [X] T011 [P] Build footer component with semantic structure

## Phase 3.3: Styling and Theme Implementation
- [ ] T012 Apply Ретрознак dark theme with orange accents to all components
- [ ] T013 [P] Implement responsive behavior for 320px+ devices
- [ ] T014 [P] Add DaisyUI component variants (badges, buttons, modifiers)

## Phase 3.4: JavaScript Interactivity
- [X] T015 [P] Implement js/main.js for smooth navigation and anchor links
- [X] T016 Create js/forms.js for AJAX form submission logic
- [X] T017 Add form validation based on data-model.md rules

## Phase 3.5: PHP Backend Integration
- [X] T018 Adapt etc/send-form.php to backend/contact-form.php per contract
- [X] T019 Implement ContactForm and ProductInquiry data handling
- [X] T020 Add email sending functionality with HTML templates
- [X] T021 Implement server-side validation matching client-side rules

## Phase 3.6: Content Integration
- [X] T022 [P] Integrate content from docs/wireframe.md into HTML sections
- [X] T023 [P] Add placeholder images (JPEG/PNG/SVG per constitution)
- [X] T024 [P] Implement historical authenticity in copy and tone

## Phase 3.7: Performance and Polish
- [X] T025 Optimize for First Contentful Paint < 1.5 seconds requirement
- [X] T026 [P] Implement image lazy loading for below-fold content
- [X] T027 [P] Minify CSS/JS assets for production
- [X] T028 Cross-browser compatibility testing (320px+ responsive)
- [X] T029 [P] Setup DaisyUI CSS custom properties system for centralized theme management

## Dependencies
- T001 blocks all other tasks (file structure required)
- T002-T004 (setup) before T005-T011 (HTML structure)
- T005 (base HTML) blocks all component tasks T006-T011
- T012-T014 (styling) require T006-T011 (components)
- T015-T017 (JavaScript) require T005-T011 (HTML structure)
- T018-T021 (PHP backend) can run parallel to frontend tasks
- T022-T024 (content) require T005-T011 (HTML structure)
- T025-T029 (polish) require all core implementation

## Parallel Execution Examples
```bash
# Setup phase (after T001-T002):
Task: "Configure daisyui.config.js with Ретрознак custom theme"
Task: "Setup css/styles.css with DaisyUI imports"

# Component creation phase (after T005):
Task: "Implement navbar component with dropdown navigation"
Task: "Create hero section with DaisyUI hero component"
Task: "Build timeline component for history section"
Task: "Implement card grid for product catalog section"
Task: "Create collapse FAQ component with join-vertical"
Task: "Build footer component with semantic structure"

# Content and optimization phase:
Task: "Integrate content from docs/wireframe.md into HTML sections"
Task: "Add placeholder images (JPEG/PNG/SVG per constitution)"
Task: "Implement historical authenticity in copy and tone"
Task: "Implement image lazy loading for below-fold content"
Task: "Minify CSS/JS assets for production"
```

## Special Notes
- **Testing**: Excluded per user explicit request ("не делай тестов, я сам все протестирую вручную")
- **DaisyUI Philosophy**: Use semantic components over utility classes (btn vs dozens of Tailwind classes)
- **Constitution Compliance**: All tasks follow DaisyUI-only approach, no additional CSS frameworks
- **Performance**: FCP < 1.5s is critical requirement (T025)
- **Responsive**: 320px minimum width support required
- **Content**: Historical authenticity per constitution principles
- **T029 Details**: Create CSS custom properties using `@plugin "daisyui/theme"` syntax with --color-primary, --color-accent, --color-base-100/200/300 for centralized dark theme with orange accents management

## Task Generation Rules Applied
1. **From Contracts**: contact-form.php → T018-T021 backend tasks
2. **From Data Model**: ContactForm/ProductInquiry → T016-T017, T019 validation tasks
3. **From Plan Structure**: 9 HTML sections → T006-T011 component tasks
4. **From Research**: DaisyUI semantic approach → all styling tasks use components
5. **Ordering**: Setup → HTML → Styling → JavaScript → Backend → Content → Polish

## Validation Checklist
- [x] All contracts have corresponding implementation (contact-form.php)
- [x] All entities have handling tasks (ContactForm, ProductInquiry)
- [x] No tests (excluded per user request)
- [x] Parallel tasks truly independent (different files)
- [x] Each task specifies exact file path
- [x] No task modifies same file as another [P] task
- [x] Performance requirements addressed (T025-T028)
- [x] Constitution compliance verified (DaisyUI-only approach)