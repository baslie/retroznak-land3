
# Implementation Plan: Лендинг для компании «Ретрознак»

**Branch**: `001-prompt-md` | **Date**: 2025-09-26 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/001-prompt-md/spec.md`

## Execution Flow (/plan command scope)
```
1. Load feature spec from Input path
   → If not found: ERROR "No feature spec at {path}"
2. Fill Technical Context (scan for NEEDS CLARIFICATION)
   → Detect Project Type from file system structure or context (web=frontend+backend, mobile=app+api)
   → Set Structure Decision based on project type
3. Fill the Constitution Check section based on the content of the constitution document.
4. Evaluate Constitution Check section below
   → If violations exist: Document in Complexity Tracking
   → If no justification possible: ERROR "Simplify approach first"
   → Update Progress Tracking: Initial Constitution Check
5. Execute Phase 0 → research.md
   → If NEEDS CLARIFICATION remain: ERROR "Resolve unknowns"
6. Execute Phase 1 → contracts, data-model.md, quickstart.md, agent-specific template file (e.g., `CLAUDE.md` for Claude Code, `.github/copilot-instructions.md` for GitHub Copilot, `GEMINI.md` for Gemini CLI, `QWEN.md` for Qwen Code or `AGENTS.md` for opencode).
7. Re-evaluate Constitution Check section
   → If new violations: Refactor design, return to Phase 1
   → Update Progress Tracking: Post-Design Constitution Check
8. Plan Phase 2 → Describe task generation approach (DO NOT create tasks.md)
9. STOP - Ready for /tasks command
```

**IMPORTANT**: The /plan command STOPS at step 7. Phases 2-4 are executed by other commands:
- Phase 2: /tasks command creates tasks.md
- Phase 3-4: Implementation execution (manual or via tools)

## Summary
Одностраничный лендинг для компании «Ретрознак» с 9 секциями, темной цветовой схемой и оранжевыми акцентами. Использует DaisyUI компоненты для всех элементов интерфейса, обеспечивает адаптивность от 320px, конверсию не менее 5%, быструю загрузку (FCP < 1.5с) и отправку заявок на email администратора. Исключены тесты по запросу пользователя.

## Technical Context
**Language/Version**: HTML5, CSS3, Vanilla JavaScript ES6+, PHP 7.4+
**Primary Dependencies**: DaisyUI компоненты с встроенной стилизацией
**Storage**: Email отправка через PHP backend (на основе etc/send-form.php)
**Testing**: Исключены по запросу пользователя ("не делай тестов, я сам все протестирую вручную")
**Target Platform**: Веб-браузеры с поддержкой минимальной ширины 320px
**Project Type**: Web лендинг (одностраничное приложение)
**Performance Goals**: First Contentful Paint < 1.5 секунд
**Constraints**: Конверсия в заявки не менее 5%, адаптивность от 320px
**Scale/Scope**: Одностраничный лендинг с 9 секциями и формами обратной связи

## Constitution Check
*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

### DaisyUI Component Architecture Compliance
- [x] All UI blocks built with DaisyUI components (hero, navbar, timeline, card, badge, button, divider)
- [x] Semantic HTML structure following DaisyUI component hierarchy (.component → .component-part → built-in styles)
- [x] No custom CSS components without extreme necessity

### DaisyUI Styling Compliance
- [x] All styling through DaisyUI components and their built-in styles only
- [x] Responsive design, hover effects, and interactions via DaisyUI component variants
- [x] No additional CSS frameworks - only DaisyUI

### Technology Stack Compliance
- [x] Uses DaisyUI components with built-in styling only
- [x] Vanilla JavaScript ES6+ for form handling and interactions
- [x] PHP backend with new script based on etc/send-form.php example
- [x] Traditional image formats only (JPEG, PNG, SVG) - no WebP/AVIF

### Historical Authenticity & Content
- [x] Content reflects Soviet-era retro signs heritage (1930-50s)
- [x] Emotional-historical communication tone maintained
- [x] Cultural authenticity preserved in all materials

## Project Structure

### Documentation (this feature)
```
specs/[###-feature]/
├── plan.md              # This file (/plan command output)
├── research.md          # Phase 0 output (/plan command)
├── data-model.md        # Phase 1 output (/plan command)
├── quickstart.md        # Phase 1 output (/plan command)
├── contracts/           # Phase 1 output (/plan command)
└── tasks.md             # Phase 2 output (/tasks command - NOT created by /plan)
```

### Source Code (repository root)
```
/
├── index.html              # Main landing page with DaisyUI components
├── css/
│   └── styles.css         # DaisyUI imports and minimal custom styles
├── js/
│   ├── main.js           # Interactivity and navigation
│   └── forms.js          # AJAX forms with PHP backend
├── images/               # JPEG/PNG/SVG placeholders
├── backend/
│   └── contact-form.php  # New PHP script based on etc/ example
├── docs/
│   └── wireframe.md     # Text wireframe project (existing)
└── daisyui.config.js     # DaisyUI configuration
```

**Structure Decision**: Выбрана архитектура веб-лендинга с DaisyUI компонентами. Все стили через DaisyUI, интерактивность на Vanilla JavaScript, формы обрабатываются PHP backend-ом. Структура соответствует конституции проекта.

## Phase 0: Outline & Research
1. **Extract unknowns from Technical Context** above:
   - For each NEEDS CLARIFICATION → research task
   - For each dependency → best practices task
   - For each integration → patterns task

2. **Generate and dispatch research agents**:
   ```
   For each unknown in Technical Context:
     Task: "Research {unknown} for {feature context}"
   For each technology choice:
     Task: "Find best practices for {tech} in {domain}"
   ```

3. **Consolidate findings** in `research.md` using format:
   - Decision: [what was chosen]
   - Rationale: [why chosen]
   - Alternatives considered: [what else evaluated]

**Output**: research.md with all NEEDS CLARIFICATION resolved

## Phase 1: Design & Contracts
*Prerequisites: research.md complete*

1. **Extract entities from feature spec** → `data-model.md`:
   - Entity name, fields, relationships
   - Validation rules from requirements
   - State transitions if applicable

2. **Generate API contracts** from functional requirements:
   - For each user action → endpoint
   - Use standard REST/GraphQL patterns
   - Output OpenAPI/GraphQL schema to `/contracts/`

3. **Generate contract tests** from contracts:
   - One test file per endpoint
   - Assert request/response schemas
   - Tests must fail (no implementation yet)

4. **Extract test scenarios** from user stories:
   - Each story → integration test scenario
   - Quickstart test = story validation steps

5. **Update agent file incrementally** (O(1) operation):
   - Run `.specify/scripts/powershell/update-agent-context.ps1 -AgentType claude`
     **IMPORTANT**: Execute it exactly as specified above. Do not add or remove any arguments.
   - If exists: Add only NEW tech from current plan
   - Preserve manual additions between markers
   - Update recent changes (keep last 3)
   - Keep under 150 lines for token efficiency
   - Output to repository root

**Output**: data-model.md, /contracts/*, quickstart.md, agent-specific file (тесты исключены по запросу)

## Phase 2: Task Planning Approach
*This section describes what the /tasks command will do - DO NOT execute during /plan*

**Task Generation Strategy**:
- Load `.specify/templates/tasks-template.md` as base
- Generate tasks from Phase 1 design docs (contracts, data model, quickstart)
- Each contract → implementation task [P]
- Each entity → model/validation creation task [P]
- Each user story → implementation task
- Implementation tasks based on wireframe sections
- **NO TESTS**: Исключены все виды тестирования по запросу пользователя

**Ordering Strategy**:
- Implementation order: Structure → Styling → Interactivity → Backend
- Dependency order: HTML structure before CSS before JavaScript before PHP
- Mark [P] for parallel execution (independent files)

**Estimated Output**: 15-20 numbered, ordered implementation tasks in tasks.md

**IMPORTANT**: This phase is executed by the /tasks command, NOT by /plan

## Phase 3+: Future Implementation
*These phases are beyond the scope of the /plan command*

**Phase 3**: Task execution (/tasks command creates tasks.md)  
**Phase 4**: Implementation (execute tasks.md following constitutional principles)  
**Phase 5**: Validation (run tests, execute quickstart.md, performance validation)

## Complexity Tracking
*Fill ONLY if Constitution Check has violations that must be justified*

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| [e.g., 4th project] | [current need] | [why 3 projects insufficient] |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient] |


## Progress Tracking
*This checklist is updated during execution flow*

**Phase Status**:
- [x] Phase 0: Research complete (/plan command)
- [x] Phase 1: Design complete (/plan command)
- [x] Phase 2: Task planning complete (/plan command - describe approach only)
- [ ] Phase 3: Tasks generated (/tasks command)
- [ ] Phase 4: Implementation complete
- [ ] Phase 5: Validation passed

**Gate Status**:
- [x] Initial Constitution Check: PASS
- [x] Post-Design Constitution Check: PASS
- [x] All NEEDS CLARIFICATION resolved
- [x] Complexity deviations documented (тесты исключены по запросу)

---
*Based on Constitution v1.1.0 - See `/memory/constitution.md`*
