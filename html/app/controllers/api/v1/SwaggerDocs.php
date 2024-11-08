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
 *             schema="Uid",
 *             @OA\Property(property="uid", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH")
 *         ),
 *         @OA\Schema(
 *             schema="Role",
 *             @OA\Property(property="uid", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH"),
 *             @OA\Property(property="role_name", type="string", nullable=false, example="mock string"),
 *             @OA\Property(property="role_name_slug", type="string", nullable=false, example="mock-string"),
 *             @OA\Property(property="description", type="string", nullable=false, example="mock description", default=""),
 *             @OA\Property(property="created_at", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="created_by", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH", default="system"),
 *             @OA\Property(property="is_activated", type="boolean", nullable=false, example=true, default=true),
 *             @OA\Property(property="is_activated_updated_at", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00", default="0000-00-00T00:00:00.000000+00:00"),
 *             @OA\Property(property="is_activated_updated_by", type="string", nullable=false, example="01JC083YJ6K9D91BW4WQQBWWZH", default="system")
 *         ),
 *         @OA\Schema(
 *             schema="RoleList",
 *             @OA\Property(property="total_count", type="integer", nullable=false, example=10, default=0),
 *             @OA\Property(property="total_pages", type="integer", nullable=false, example=10, default=0),
 *             @OA\Property(property="page", type="integer", nullable=false, example=1, default=1),
 *             @OA\Property(property="size", type="integer", nullable=false, example=10, default=10),
 *             @OA\Property(property="has_next_page", type="boolean", nullable=false, example=true, default=false),
 *             @OA\Property(property="list", type="array", nullable=false, @OA\Items(ref="#/components/schemas/Role", default="[]"))
 *         ),
 *         @OA\Schema(
 *             schema="CreateRoleParams",
 *             @OA\Property(property="role_name", type="string", nullable=false, example="mock string", minLength=5, maxLength=100),
 *             @OA\Property(property="description", type="string", nullable=true, example="mock description", minLength=1, maxLength=300, default=null)
 *         ),
 *        @OA\Schema(
 *             schema="MetadataSuccessApiResponse",
 *             @OA\Property(property="status", type="integer", nullable=false, example=200),
 *             @OA\Property(property="message", type="string", nullable=false, example="OK"),
 *             @OA\Property(property="timestamp", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/MetadataOperations", nullable=false)
 *         ),
 *         @OA\Schema(
 *             schema="MetadataOperations",
 *             @OA\Property(property="operations", type="object",  ref="#/components/schemas/Metadata", nullable=false, example="{}", default="{}"),
 *         ),
 *         @OA\Schema(
 *             schema="Metadata",
 *             @OA\Property(property="list", type="object",  ref="#/components/schemas/ListMetadataProperty", nullable=false, example="{}", default="{}"),
 *         ),
 *         @OA\Schema(
 *             schema="ListMetadataProperty",
 *             @OA\Property(property="sort_able_properties", type="array", nullable=false, @OA\Items(type="string", nullable=true, example="created_at"), default="[]"),
 *             @OA\Property(property="range_able_properties", type="array", nullable=false, @OA\Items(type="string", nullable=true, example="created_at"), default="[]")
 *         ),
 *         @OA\Schema(
 *             schema="UpdateRoleParams",
 *             @OA\Property(property="role_name", type="string", nullable=true, example="mock string", minLength=5, maxLength=100, default=null),
 *             @OA\Property(property="description", type="string", nullable=true, example="mock description", minLength=1, maxLength=300, default=null),
 *             @OA\Property(property="is_activated", type="boolean", nullable=true, example="mock description", minLength=1, maxLength=300, default=null),
 *         ),
 *         @OA\Schema(
 *             schema="UidSuccessApiResponse",
 *             @OA\Property(property="status", type="integer", nullable=false, example=200),
 *             @OA\Property(property="message", type="string", nullable=false, example="OK"),
 *             @OA\Property(property="timestamp", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="data", ref="#/components/schemas/Uid", nullable=true)
 *         ),
 *        @OA\Schema(
 *             schema="RoleListSuccessApiResponse",
 *             @OA\Property(property="status", type="integer", nullable=false, example=200),
 *             @OA\Property(property="message", type="string", nullable=false, example="OK"),
 *             @OA\Property(property="timestamp", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/RoleList", nullable=false)
 *         ),
 *         @OA\Schema(
 *             schema="RoleSuccessApiResponse",
 *             @OA\Property(property="status", type="integer", nullable=false, example=200),
 *             @OA\Property(property="message", type="string", nullable=false, example="OK"),
 *             @OA\Property(property="timestamp", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *             @OA\Property(property="data", ref="#/components/schemas/Role", nullable=true)
 *         ),
 *         @OA\Schema(
 *              schema="SuccessResponse",
 *              @OA\Property(property="status", type="integer", nullable=false, example=200),
 *              @OA\Property(property="message", type="string", nullable=false, example="OK"),
 *              @OA\Property(property="timestamp", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *              @OA\Property(property="data", type="object", nullable=true, example=null)
 *         ),
 *         @OA\Schema(
 *              schema="ErrorResponse",
 *              @OA\Property(property="status", type="integer", nullable=false, example=400),
 *              @OA\Property(property="message", type="string", nullable=false, example="mock error message"),
 *              @OA\Property(property="timestamp", type="string", nullable=false, example="2024-11-06T08:05:38.486431+00:00"),
 *              @OA\Property(property="data", type="object", nullable=true, example=null)
 *         )
 *     }
 * )
 */
class SwaggerDocs
{
    // This class is intentionally left blank
}
