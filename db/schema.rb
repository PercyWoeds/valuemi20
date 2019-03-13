# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20190311171545) do

  # These are extensions that must be enabled in order to support this database
  enable_extension "plpgsql"

  create_table "clients", force: :cascade do |t|
    t.string   "vcodigo"
    t.string   "vruc"
    t.string   "vrazon2"
    t.string   "vdireccion"
    t.string   "vdistrito"
    t.string   "vprov"
    t.string   "vdep"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
    t.string   "mailclient"
    t.string   "mailclient2"
    t.string   "mailclient3"
  end

  create_table "credits", force: :cascade do |t|
    t.datetime "fecha"
    t.string   "code"
    t.integer  "nota_id"
    t.string   "motivo"
    t.float    "subtotal"
    t.float    "tax"
    t.float    "total"
    t.integer  "moneda_id"
    t.string   "mod_factura"
    t.integer  "mod_tipo"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
    t.string   "processed"
    t.string   "tipo"
    t.string   "description"
    t.integer  "customer_id"
  end

  create_table "invoices", force: :cascade do |t|
    t.string   "cliente"
    t.date     "fecha"
    t.string   "td"
    t.string   "serie"
    t.string   "numero"
    t.decimal  "preciocigv"
    t.decimal  "preciosigv"
    t.float    "cantidad"
    t.float    "vventa"
    t.float    "igv"
    t.float    "importe"
    t.string   "ruc"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string   "guia"
    t.string   "flag1"
    t.text     "nombre"
    t.string   "flag2"
  end

  create_table "locals", force: :cascade do |t|
    t.string   "code"
    t.string   "descrip"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string   "numero"
    t.string   "serie"
  end

  create_table "mailings", force: :cascade do |t|
    t.string   "td"
    t.string   "serie"
    t.string   "numero"
    t.string   "ruc"
    t.boolean  "flag"
    t.string   "flag1"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "monedas", force: :cascade do |t|
    t.string   "description"
    t.string   "descrip_short"
    t.datetime "created_at",    null: false
    t.datetime "updated_at",    null: false
  end

  create_table "nota", force: :cascade do |t|
    t.string   "code"
    t.string   "descrip"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "notacredits", force: :cascade do |t|
    t.datetime "fecha"
    t.string   "code"
    t.integer  "nota_id"
    t.string   "motivo"
    t.float    "subtotal"
    t.float    "tax"
    t.float    "total"
    t.integer  "moneda_id"
    t.string   "mod_factura"
    t.integer  "mod_tipo"
    t.string   "processed"
    t.string   "tipo"
    t.string   "description"
    t.integer  "customer_id"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
    t.float    "quantity"
    t.float    "price"
    t.float    "client_id"
    t.integer  "notum_id"
    t.string   "nombre"
  end

  create_table "notes", force: :cascade do |t|
    t.string   "td"
    t.datetime "fecha"
    t.string   "turno"
    t.string   "cod_emp"
    t.string   "caja"
    t.string   "serie"
    t.string   "numero"
    t.string   "cod_cli"
    t.string   "ruc"
    t.string   "placa"
    t.string   "odometro"
    t.string   "cod_prod"
    t.float    "cantidad"
    t.float    "precio_sigv"
    t.float    "precio"
    t.float    "vventa"
    t.float    "tax"
    t.float    "importe"
    t.float    "igv"
    t.string   "fpago"
    t.string   "descrip"
    t.string   "tk_devol"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
    t.float    "implista"
    t.string   "cod_tar"
    t.string   "km"
    t.string   "cod_sucu"
    t.string   "isla"
    t.string   "dni_cli"
    t.string   "chofer"
    t.string   "procesado"
    t.string   "tipo"
    t.string   "rscliente"
  end

  create_table "products", force: :cascade do |t|
    t.string   "code"
    t.string   "name"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "users", force: :cascade do |t|
    t.string   "email",                  default: "", null: false
    t.string   "encrypted_password",     default: "", null: false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",          default: 0,  null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.string   "name"
    t.string   "permission_level"
    t.datetime "created_at",                          null: false
    t.datetime "updated_at",                          null: false
    t.string   "ruc"
  end

  add_index "users", ["email"], name: "index_users_on_email", unique: true, using: :btree
  add_index "users", ["reset_password_token"], name: "index_users_on_reset_password_token", unique: true, using: :btree

  create_table "voideds", force: :cascade do |t|
    t.string   "numero"
    t.string   "serie"
    t.string   "factura"
    t.date     "fecha"
    t.text     "texto"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

end
