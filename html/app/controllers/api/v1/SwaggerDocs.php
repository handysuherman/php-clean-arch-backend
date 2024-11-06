<?php



namespace app\controllers\api\v1;

use OpenApi\Annotations as OA;

/**
 * @OA\Components(
 *     securitySchemes={
 *         @OA\SecurityScheme(
 *             securityScheme="ApiKey",
 *             type="apiKey",
 *             in="header",
 *             name="x-api-key"
 *         ),
 *         @OA\SecurityScheme(
 *             securityScheme="Token",
 *             type="apiKey",
 *             in="header",
 *             name="x-token"
 *         )
 *     },
 *     schemas={
 *         @OA\Schema(
 *             schema="Role",
 *             @OA\Property(property="uid", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH"),
 *             @OA\Property(property="role_name", type="string", nullable=false, example="mock string"),
 *             @OA\Property(property="role_name_slug", type="string", nullable=false, example="mock-string"),
 *             @OA\Property(property="description", type="string", nullable=false, example="mock description"),
 *             @OA\Property(property="created_at", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="created_by", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH"),
 *             @OA\Property(property="is_activated", type="boolean", nullable=false, example=true),
 *             @OA\Property(property="is_activated_updated_at", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="is_activated_updated_by", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH")
 *         ),
 *         @OA\Schema(
 *             schema="RoleSuccessApiResponse",
 *             @OA\Property(property="status", type="integer"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="timestamp", type="integer"),
 *             @OA\Property(property="data", ref="#/components/schemas/Role", nullable=true)
 *         ),
 *         @OA\Schema(
 *              schema="SuccessResponse",
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="OK"),
 *              @OA\Property(property="timestamp", type="string", example="2024-11-06T08:05:38.486431+00:00"),
 *              @OA\Property(property="data", type="object", nullable=true, example=null)
 *         ),
 *         @OA\Schema(
 *              schema="ErrorResponse",
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="any error message goes here"),
 *              @OA\Property(property="timestamp", type="string", example="2024-11-06T08:05:38.486431+00:00"),
 *              @OA\Property(property="data", type="object", nullable=true, example=null)
 *         )
 *     }
 * )
 */
class SwaggerDocs
{
    // This class is intentionally left blank
}