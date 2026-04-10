<?php

namespace App\Http\Controllers;

use App\Models\DoctorSpecializedCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DoctorSpecializedCategoryController extends Controller
{
    /**
     * Get all specialized categories
     */
    public function index(Request $request)
    {
        $categories = DoctorSpecializedCategory::paginate($request->per_page ?? 15);
        return response()->json($categories, 200);
    }

    /**
     * Create new specialized category (Admin only)
     */
    public function store(Request $request)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can create categories',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:doctors_specialized_categories',
            ]);

            $category = DoctorSpecializedCategory::create($validated);

            return response()->json([
                'message' => 'Category created successfully',
                'category' => $category,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get category by ID
     */
    public function show($id)
    {
        $category = DoctorSpecializedCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category, 200);
    }

    /**
     * Update category (Admin only)
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can update categories',
            ], 403);
        }

        try {
            $category = DoctorSpecializedCategory::find($id);

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:doctors_specialized_categories,name,' . $id,
            ]);

            $category->update($validated);

            return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Delete category (Admin only)
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can delete categories',
            ], 403);
        }

        $category = DoctorSpecializedCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
