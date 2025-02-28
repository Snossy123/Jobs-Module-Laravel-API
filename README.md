# Job Management Module Documentation

## Overview
This document provides an overview of the enhancements and features implemented in the Job Management module. These changes introduce salary structures, validation rules, job application processes, and middleware enhancements to improve system functionality and maintainability.

---

## Features Implemented

### 1. Salary Management
- **Added `SalaryType` Enum**
  - Defines salary types: `static`, `range`, `sentence`.
- **Created `Salary` Model**
  - Stores salary-related data with attributes: `type`, `value`, `from`, `to`, and `description`.
- **Updated `Jobs` Table**
  - Replaced `salary` column with `salary_id` (foreign key to `salaries` table).
- **Updated `JobResource`**
  - Includes salary details with structured fallback values.

### 2. Job Validation Enhancements
- **Updated `JobRequest`**
  - Implements validation rules for job attributes.
  - Includes `validateSalary()` method for salary type-specific checks.
  - Implements tag normalization and redundancy checks in `prepareTags()`.
- **Updated `JobRepositoryInterface`**
  - Added `checkCityInCountry` method for city-country validation.
- **Updated `JobService`**
  - Integrated city-country validation logic.

### 3. Job Application and Status Management
- **Created `JobApplyController`**
  - Handles job applications and job status updates.
- **Created `JobApplyMiddleware`**
  - Restricts multiple applications per user.
  - Prevents job updates if applications exist.
- **Created `JobApplyRequest`**
  - Validates job application data.
- **Updated `JobApplyService`**
  - Manages business logic for job applications.
- **Created `JobApply` Table**
  - Stores job applications with `job_id`, `user_id`, and `CV`.

### 4. Job Retrieval and Middleware Enhancements
- **Implemented `JobController::show`**
  - Retrieves job details through `JobService::show`.
- **Removed `SpecialJobsRequest`**
  - Cleanup for unused files.
- **Updated `JobRepositoryInterface`**
  - Added `find` method for job retrieval.
- **Implemented `JobRepository::find`**
  - Handles job retrieval with exception handling and logging.
- **Created `JobManageMiddleware`**
  - Enforces access control for job management based on `created_by_user_id`.
- **Updated API Routes**
  - Added `/edit` and `/delete` endpoints with access control.

---

## Validation Rules for Job Request Attributes

### General Rules

1. **`job_title`**
   - Required, string, 3-255 characters.
2. **`job_role`**
   - Required, string, 3-255 characters.
3. **`tags`**
   - Optional, max 5 tags, must match `^[A-Z]+(-[A-Z]+)*$`.
4. **`salary`**
   - Required, follows type-specific validation:
     - **Static**: `value` must be numeric (0-1,000,000).
     - **Range**: `from` and `to` must be numeric (0-1,000,000), `to` >= `from`.
     - **Sentence**: `description` must be a string, max length 500.
5. **`vacancies`**
   - Required, integer, min 0, max 1000.
6. **`years_experience_from` & `years_experience_to`**
   - Required, integer, max 50, `to` >= `from`.
7. **`work_place_type`**
   - Required, must be a valid predefined value.

---

## Key Improvements
- **Middleware for Access Control**: Ensures only job creators can edit/delete jobs.
- **Better Error Handling**: Improved exception management in repositories and services.
- **Database Normalization**: Structured salary information in a separate table.
- **Security Enhancements**: Middleware restrictions for job updates when applications exist.
- **API Documentation**: Write Documentation for Developed API endpoints [check it here](https://documenter.getpostman.com/view/39303300/2sAYQZGrdv#da6b7083-4961-4da7-8856-3efbc4730d39)
---

## Conclusion
These enhancements significantly improve job management by adding flexible salary structures, refining job validation, and enforcing better application constraints. Future improvements can focus on optimizing query performance, adding reporting features, and expanding search filters.



